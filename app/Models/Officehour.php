<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Officehour extends Model
{
    use HasFactory;

    protected $table = 'officehour';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'dayslot',
        'weekday',
        'open',
        'close',
        'is_closed',
        'nps',
        'doctor_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($officeHour) {
            // Handle logic for creating or updating session hours
            $officeHour->handleSessionHours();
        });

        static::deleted(function ($officeHour) {
            // Handle logic for deleting session hours
            $officeHour->deleteSessionHours();
        });
    }
    /**
     * Handle session hours creation or update.
     */
    public function handleSessionHours()
    {
        // Get the dates for the next six weeks
        $dates = Carbon::now()->startOfWeek()->addWeek()->startOfDay()->toPeriod('6 weeks')->weekdays();
        foreach ($dates as $date) {
            if ($date->dayOfWeekIso == $this->dayslot) {
                SessionHour::updateOrCreate([
                    'date' => $date->toDateString(),
                    'officehour_id' => $this->id,
                ], [
                    'open' => $this->open,
                    'close' => $this->close,
                    'is_closed' => $this->is_closed,
                    'nps' => $this->nps,
                    'reason' => $this->reason,
                    'header' => $this->getSessionHeader(),
                    'active' => true,
                    'doctor_id' => $this->doctor_id,
                ]);
            }
        }
    }
    /**
     * Handle session hours deletion.
     */
    public function deleteSessionHours()
    {
        // Delete session hours
        SessionHour::where('officehour_id', $this->id)
            ->update(['active' => false]);

        // Handle fallback to other active hours if necessary
        $this->fallbackToActiveHours();
    }

    private function fallbackToActiveHours()
    {
        $dates = Carbon::now()->startOfWeek()->addWeek()->startOfDay()->toPeriod('6 weeks')->weekdays();
        foreach ($dates as $date) {
            // Check if there are no active session hours for the date
            if (!SessionHour::where('date', $date->toDateString())->where('active', true)->exists()) {
                // Activate fallback office hours
                SessionHour::where('date', $date->toDateString())
                    ->where('officehour_id', $this->id)
                    ->update(['active' => true]);
            }
        }
    }
    /**
     * Get session header.
     */
    private function getSessionHeader(): string
    {
        $closed = $this->reason ? 'CLOSED: ' . $this->reason : 'CLOSED';
        $hours = 'Hours: ' . Carbon::parse($this->open)->format('h:i A') . ' - ' . Carbon::parse($this->close)->format('h:i A');
        return $this->is_closed ? $closed : $hours;
    }

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function modifiedHours()
    {
        return $this->hasMany(ModifiedHour::class, 'officehour_id');
    }

    public function sessionHours()
    {
        return $this->hasMany(SessionHour::class, 'officehour_id');
    }
}
