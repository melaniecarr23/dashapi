<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Day extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'day';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $dates = [
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    protected $fillable = [
        'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($day) {
            \Log::info("Day created event triggered for ID: " . $day->id);
            $day->createSessionHours();
        });

        static::saved(function ($day) {
            \Log::info('Day saved event triggered for ID: ' . $day->id);
            $day->createSessionHours();
        });

        \Log::info('Day model boot method executed.');

    }

    public function createSessionHours()
    {
        \Log::info('Creating session hours for day: ' . $this->date);

        // First, handle modified hours if they exist
        $hours = ModifiedHour::whereDate('date', $this->date)->get()->isNotEmpty()
            ? ModifiedHour::whereDate('date', $this->date)->get()
            : Officehour::where('dayslot', $this->date->dayOfWeekIso)->get();

            $dateString = $this->date->toDateString();
            foreach ($hours as $hour) {
                $isModified = isset($hour->reason);
                SessionHour::updateOrCreate([
                    'date' => $dateString,
                    'modified_hour_id' => $isModified ? $hour->id : null,
                    'officehour_id' => $isModified ? $hour->officehour_id : $hour->id
                ],
                    [
                    'date' => $dateString,
                    'open' => Carbon::parse($dateString . ' ' . $hour->open),
                    'close' => Carbon::parse($dateString . ' ' . $hour->close),
                    'is_closed' => $hour->is_closed,
                    'nps' => $hour->nps,
                    'day_id' => $this->id,
                    'doctor_id' => $hour->doctor_id || 1,
                    'header' => $this->getSessionHeader($hour) || 'NO HEADER',
                    'reason' => isset($hour->reason) ? $hour->reason : null
                ]);
            }
        }

    public function getSessionHeader($hour): string
    {
        \Log::info("Get session header for hour ID: {$hour->id}");
        $closed = $hour->is_closed ? 'CLOSED: ' . ($hour->reason ?? 'No Reason') : null;
        $hours = 'Hours: ' . Carbon::parse($hour->open)->format('h:i A') . ' - ' . Carbon::parse($hour->close)->format('h:i A');
        \Log::info("Get session header for hour ID: {$hour->id}, Closed: {$closed}, Hours: {$hours}");
        return $hour->is_closed ? $closed : $hours;
    }

    public function sessionHours() {
        return $this->hasMany(SessionHour::class, 'day_id');
    }

}


