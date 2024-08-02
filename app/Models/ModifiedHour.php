<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifiedHour extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'modified_hour';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $dates = [
        'date',
        'open',
        'close',
    ];

    protected $fillable = [
        'date',
        'open',
        'close',
        'is_closed',
        'nps',
        'reason',
        'officehour_id',
        'doctor_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($modifiedHour) {
            // Handle logic for creating or updating session hours
            $modifiedHour->handleSessionHours();
        });

        static::deleted(function ($modifiedHour) {
            // Handle logic for deleting session hours
            $modifiedHour->deleteSessionHours();
        });
    }

    public function handleSessionHours()
    {
        $dateString = $this->date;

        // Mark corresponding office hours as inactive
        SessionHour::where('date', $dateString)
            ->where('officehour_id', $this->officehour_id)
            ->update(['active' => false]);

        // Create or update session hours
        SessionHour::updateOrCreate([
            'date' => $dateString,
            'modified_hour_id' => $this->id,
        ], [
            'open' => $this->open,
            'close' => $this->close,
            'is_closed' => $this->is_closed,
            'nps' => $this->nps,
            'reason' => $this->reason,
            'header' => $this->getSessionHeader(),
            'active' => true,
            'officehour_id' => $this->officehour_id,
            'doctor_id' => $this->doctor_id,
        ]);
    }

    public function deleteSessionHours()
    {
        $dateString = $this->date->toDateString();

        // Delete session hours
        SessionHour::where('date', $dateString)
            ->where('modified_hour_id', $this->id)
            ->delete();

        // Activate fallback office hours if no active session hours exist for the date
        if (SessionHour::where('date', $dateString)->where('active', true)->doesntExist()) {
            SessionHour::where('date', $dateString)
                ->where('officehour_id', $this->officehour_id)
                ->update(['active' => true]);
        }
    }

    // Relationships
    public function officehour()
    {
        return $this->belongsTo(Officehour::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function sessionHours()
    {
        return $this->hasMany(SessionHour::class, 'modified_hour_id');
    }

    private function getSessionHeader(): string
    {
        $closed = $this->reason ? 'CLOSED: ' . $this->reason : 'CLOSED';
        $hours = 'Hours: ' . Carbon::parse($this->open)->format('h:i A') . ' - ' . Carbon::parse($this->close)->format('h:i A');
        return $this->is_closed ? $closed : $hours;
    }
}
