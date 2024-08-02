<?php

namespace App\Models;

use App\Notifications\AppointmentAffected;
use App\Notifications\AppointmentConfirmation;
use App\Notifications\AppointmentsNeedRescheduled;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appt extends Model
{
    use SoftDeletes;
    protected $table = 'appointment';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $casts = [
        'patient_id' => 'int',
        'type_id' => 'int',
        'status_id' => 'int',
        'doctor_id' => 'int',
        'officehour_id' => 'int',
        'date_time' => 'datetime:Y-m-d H:i:s',
        'end_time' => 'datetime:Y-m-d H:i:s'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_time',
        'end_time'
    ];

    protected $fillable = [
        'patient_id',
        'date_time',
        'end_time',
        'note',
        'session_hour_id',
        'type_id',
        'status_id',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'updated_at',
        'doctor_id',
        'officehour_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($appointment) {
            // send appointment confirmation
            static::created(function ($appointment) {
                if ($appointment->status_id == 2) {
                    $appointment->patient->notify(new AppointmentConfirmation($appointment));
                }
            });

            $appointment->handleBilling();
        });

        static::updated(function ($appointment) {
            $originalStatus = $appointment->getOriginal('status_id');
            $newStatus = $appointment->status_id;
            $isFuture = $appointment->date_time->gt(Carbon::now());
            // if rescheduled or canceled, add in new timeslot
            if ($isFuture && in_array($newStatus, [3, 4]) && !in_array($originalStatus, [3, 4])) {
                // Create a new unbooked appointment slot with the same type_id
                $newAppointment = $appointment->replicate(['patient_id', 'status_id']);
                $newAppointment->patient_id = null;
                $newAppointment->status_id = 0; // set to available
                $newAppointment->save();
            }
            $appointment->handleBilling();
            // booked appointment, send confirmation
            if ($originalStatus == 0 && $newStatus == 2) {
                $appointment->patient->notify(new AppointmentConfirmation($appointment));
            }
        });

        static::deleted(function ($appointment) {
            $appointment->handleBilling();
        });
    }

    public function handleBilling()
    {
        if(is_null($this->patient)) {
            return;
        }
        $patient = $this->patient;
        $plan = $patient->plan;
        $billingDay = $patient->payment_day;
        $today = Carbon::today();
        $startDate = $billingDay === 1 ? $today->copy()->startOfMonth() : $today->copy()->startOfMonth()->addDays(14);
        $endDate = $billingDay === 1 ? $today->copy()->endOfMonth() : $today->copy()->addMonth()->startOfMonth()->addDays(14);

        // Skip billing if the plan amount is $0
        if ($plan->amount == 0) {
            return;
        }

        $appointments = Appt::whereIn('patient_id', $patient->family->pluck('id'))
            ->whereBetween('date_time', [$startDate, $endDate])
            ->whereIn('status_id', [1, 2]) // assuming 1 and 2 are booked statuses
            ->get();

        if ($appointments->isNotEmpty()) {
            // Calculate the amount based on the plan and the number of unique patients with appointments
            $uniquePatients = $appointments->pluck('patient_id')->unique()->count();
            $amount = $this->calculateAmount($plan, $uniquePatients);

            // Create a payment record if there are appointments and no existing payment
            if (!$this->paymentExists($patient->id, $startDate, $endDate)) {
                Payment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $patient->doctor_id,
                    'date' => $today,
                    'due_date' => $billingDay === 1 ? $today->copy()->endOfMonth() : $today->copy()->startOfMonth()->addDays(14),
                    'method' => $patient->plan_id,
                    'amount' => $amount,
                    'status' => 'due',
                ]);
            }
        } else {
            // Remove payment record if no appointments exist
            Payment::where('patient_id', $patient->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->delete();
        }
    }

    private function paymentExists($patient_id, $startDate, $endDate)
    {
        return Payment::where('patient_id', $patient_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->exists();
    }

    private function calculateAmount($plan, $uniquePatients)
    {
        $baseAmount = $plan->amount;
        $additionalCost = $plan->additional_member_cost;
        $memberLimit = $plan->member_limit;

        // If the plan is per visit (e.g., plan_id 10), calculate based on unique patients
        if ($plan->id == 10) {
            return $baseAmount * $uniquePatients;
        }

        // Calculate for other plans
        if ($uniquePatients <= $memberLimit) {
            return $baseAmount;
        } else {
            return $baseAmount + ($additionalCost * ($uniquePatients - $memberLimit));
        }
    }

    // Relationships
    public function parent()
    {
        return $this->patient->parent;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function type()
    {
        return $this->belongsTo(ApptType::class);
    }

    public function status()
    {
        return $this->belongsTo(ApptStatus::class);
    }

    public function family()
    {
        return $this->patient->family;
    }

    public function patient_name()
    {
        $fullName = $this->patient()->get('first') . ' ' . $this->patient()->get('last');
        return $fullName;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'patient_id', 'patient_id')->orderBy('due_date', 'desc');
    }

    public function appts()
    {
        return $this->hasMany(Appt::class, 'patient_id', 'patient_id')->orderBy('date_time', 'desc');
    }

    public function hasFutureAppt()
    {
        return Appt::whereDate('date_time', '>', new Carbon('today'))
            ->where(['patient_id' => $this->patient_id, 'status_id' => 2])
            ->orderBy('date_time', 'asc')
            ->first();
    }

    public function doctor(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'doctor_id');
    }

    public function officehour(): BelongsTo
    {
        return $this->belongsTo(Officehour::class, 'officehour_id');
    }

    public function sessionhour(): BelongsTo
    {
        return $this->belongsTo(SessionHour::class, 'session_hour_id');
    }

    // Scopes
    public function scopeWithStatus($query, $statusId)
    {
        return $query->where('status_id', $statusId);
    }

    public function scopeUnbookedPast(Builder $query)
    {
        $dt = Carbon::now();
        $query->where('date_time', '<', $dt)->where('status_id', '=', 0)->whereNull('patient_id');
    }

    public function scopeScheduled(Builder $query)
    {
        $query->whereIn('status_id', [1, 2, 7]);
    }

    public function scopeFam(Builder $query, $family)
    {
        $query->whereIn('patient_id', $family);
    }

    public function scopeBooked(Builder $query)
    {
        $query->where('status_id', 2);
    }

    public function scopeFamAppts(Builder $query, Patient $patient)
    {
        $query->whereIn('patient_id', $patient->family->modelKeys())->orderByDesc('id');
    }

    public function scopeHour(Builder $query, $hourId)
    {
        $query->where('session_hour_id', $hourId);
    }
    public function scopeInsideHours(Builder $query, $startTime, $endTime)
    {
        $query->where('date_time', '>=', $startTime)->orWhere('date_time', '<=', $endTime);
    }
    public function scopeOutsideHours(Builder $query, $startTime, $endTime)
    {
        $query->where('date_time', '<', $startTime)->orWhere('date_time', '>', $endTime);
    }
    public function scopePast(Builder $query)
    {
        $query->where('date_time', '<', Carbon::now());
    }
    public function scopeFuture(Builder $query)
    {
        $query->where('date_time', '>', Carbon::now());
    }

    public function scopeUnavailable(Builder $query)
    {
        $query->whereIn('status_id',[1,2,7]);
    }

    public function scopeEditable(Builder $query)
    {
        $query->whereNotIn('status_id', [3, 4]);
    }

    public function scopeDeletable(Builder $query)
    {
        $query->where('status_id', 0);
    }
}
