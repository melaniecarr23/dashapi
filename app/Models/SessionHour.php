<?php

namespace App\Models;

use App\Notifications\AppointmentAffected;
use App\Notifications\AppointmentsNeedRescheduled;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionHour extends Model
{
    protected $table = 'session_hour';
    public $timestamps = true;
    public $primaryKey = 'id';

    protected $dates = ['date', 'open', 'close'];

    protected $casts = [
        'is_closed' => 'boolean',
        'active' => 'boolean',
    ];

    protected $fillable = [
        'date',
        'open',
        'close',
        'is_closed',
        'nps',
        'reason',
        'header',
        'active',
        'modified_hour_id',
        'officehour_id',
        'doctor_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($sessionHour) {
            \Log::info("SessionHour ID: " . $sessionHour->id . " - Active: " . $sessionHour->active . ", Is Closed: " . $sessionHour->is_closed . ", Open: " . $sessionHour->open . ", Close: " . $sessionHour->close);

            if ($sessionHour->officehour_id === 3) {
                \Log::info("Wednesday: " . $sessionHour->date . '\nofficehour: ' . '\n Is Closed:' . $sessionHour->is_closed . '\n Open: ' . $sessionHour->open . ' \nClose' . $sessionHour->close);
            }

            if ($sessionHour->active && !$sessionHour->is_closed && !is_null($sessionHour->open) && !is_null($sessionHour->close)) {
                $sessionHour->updateExistingAppointmentsWithSessionId($sessionHour);
                \Log::info("Conditions met for syncing appointments.");
                $sessionHour->syncAppointments(Carbon::parse($sessionHour->open), Carbon::parse($sessionHour->close));
            } else {
                \Log::info("SessionHour ID: " . $sessionHour->date . " is either closed or inactive.");
            }
        });

        static::deleted(function ($sessionHour) {
            \Log::info("SessionHour deleted event triggered for ID: " . $sessionHour->id);
            if (!is_null($sessionHour->open) && !is_null($sessionHour->close)) {
                $sessionHour->deleteAndNotifyAppointments(Carbon::parse($sessionHour->open), Carbon::parse($sessionHour->close));
            }
        });
    }
    public function updateExistingAppointmentsWithSessionId($hour) {
        Appt::future()->insideHours(Carbon::parse($hour->start), Carbon::parse($hour->end))->update(['session_hour_id'=> $hour->id]);
        \Log::info('Session Hour Added to existing appointments');
    }
    public function syncAppointments($newStartTime, $newEndTime)
    {
//        \Log::info("syncAppointments called for SessionHour ID: " . $this->id . " with Open: " . $this->open . " and Close: " . $this->close);
//        \Log::info("syncAppointments called for Officehour ID: " . $this->officehour_id . " with newStartTime: " . $newStartTime . " and newEndTime: " . $newEndTime);

        if ($this->open == null) {
//            \Log::info('Session is closed. No appointments will be created for session hour ID: ' . $this->id);
            return;
        }


        $existingAppointments = $this->appts()->future()->unavailable()->pluck('date_time')->toArray();

        // Add new timeslots that don't exist
        $timesToAdd = CarbonPeriod::create($newStartTime, '5 minutes', $newEndTime)->filter(function ($time) use ($existingAppointments) {
            return !in_array($time->toDateTimeString(), $existingAppointments);
        });

        foreach ($timesToAdd as $time) {
            $nps = $this->nps && $time->eq($this->close);
            $appt = Appt::firstOrCreate([
                'patient_id' => null,
                'date_time' => $time->copy(),
                'end_time' => $nps ? $time->copy()->addMinutes(45) : $time->copy()->addMinutes(5),
                'session_hour_id' => $this->id,
                'officehour_id' => $this->officehour_id,
                'doctor_id' => $this->doctor_id,
                'status_id' => 0,
                'type_id' => $nps ? 4 : 2
            ]);
            \Log::info('Created appointment ID: ' . $appt->id . ' for session hour ID: ' . $this->id . ' at ' . $appt->date_time);
        }
        // Delete deletable timeslots outside session hours
        $this->deleteAndNotifyAppointments($newStartTime, $newEndTime);
    }

    private function deleteAndNotifyAppointments($newStartTime, $newEndTime)
    {
        \Log::info('Deleting and notifying appointments for session hour ID: ' . $this->id);

        // Delete deletable timeslots outside session hours
        Appt::hour($this->id)->future()->deletable()
            ->outsideHours($newStartTime, $newEndTime)
            ->delete();

        // Notify reschedulable appointments
        $notify = Appt::with('patient')->hour($this->id)->future()->booked()
            ->outsideHours($newStartTime, $newEndTime)
            ->get();

        foreach ($notify as $appt) {
            $patient = $appt->patient;
            $doctor = $appt->doctor;
            // mark appointment as rescheduled
//            $appt->status_id = 3;
            $appt->save();
            if ($patient) {
                \Log::info("Notifying doctor for appointment ID: " . $appt->id . " that needs to be rescheduled.");
                //$doctor->notify(new AppointmentAffected($appt));
            }
        }

        if ($notify->isNotEmpty()) {
            $patients = $notify->pluck('patient.nickname')->toArray();
            $doctor = $notify->first()->doctor;
            $list = implode(", ", $patients);
            $date = $notify->first()->date_time->toDateString();
            \Log::info("Notifying doctor of all appointments needing rescheduled for: " . $date);
            //$doctor->notify(new AppointmentsNeedRescheduled($list, $date));
        }
    }

    // RELATIONS AND SCOPES

    public function officehour(): BelongsTo
    {
        return $this->belongsTo(Officehour::class);
    }

    public function modifiedHour(): BelongsTo
    {
        return $this->belongsTo(ModifiedHour::class, 'modified_hour_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appts(): HasMany
    {
        return $this->hasMany(Appt::class);
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', Carbon::now()->startOfDay()->toDateString());
    }

    public function scopeSessionDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeOpen($query)
    {
        return $query->where('is_closed', false)
            ->whereNotNull('open')
            ->whereNotNull('close');
    }
}

