<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Appointment
 *
 * @property int $id
 * @property int|null $patient_id
 * @property int|null $officehour_id
 * @property int|null $doctor_id
 * @property Carbon|null $appt_date
 * @property Carbon|null $appt_time
 * @property Carbon|null $appt_carbon
 * @property string|null $appt_note
 * @property Carbon|null $date_time
 * @property bool|null $appt_reminder
 * @property string|null $reminder_cell
 * @property Carbon|null $created_at
 * @property int|null $appt_type_id
 * @property int|null $appt_status_id
 * @property string|null $subjective
 * @property string|null $objective_text
 * @property string|null $assessment
 * @property string|null $plan
 * @property string $clearUC
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read ApptStatus|null $appt_status
 * @property-read Officehour|null $officehour
 * @property-read Patient $patient
 * @property-read ApptType $appt_type
 * @property-read User|null $doctor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Appt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptCarbon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptReminder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereClearUC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereObjectiveText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereSubjective($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereOfficehourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereReminderCell($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereDeletedAt($value)
 *
 * @mixin Eloquent
 */
class Appointment extends Model
{
    use SoftDeletes;

    protected $table = 'appointment';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $dates = [
        'appt_date',
        'created_at',
        'updated_at',
        'date_time',
    ];

    protected $fillable = [
        'patient_id',
        'appt_carbon',
        'date_time',
        'appt_date',
        'appt_time',
        'appt_note',
        'appt_reminder',
        'reminder_cell',
        'appt_type_id',
        'appt_status_id',
        'subjective',
        'objective_text',
        'assessment',
        'plan',
        'clearUC',
        'updated_at',
        'doctor_id',
        'officehour_id',
    ];

    protected function casts(): array
    {
        return [
            'patient_id' => 'int',
            'appt_reminder' => 'bool',
            'appt_type_id' => 'int',
            'appt_status_id' => 'int',
            'appt_date' => 'datetime:Y-m-d',
            'appt_time' => 'datetime:h:i A',
            'doctor_id' => 'int',
            'officehour_id' => 'int',
            'date_time' => 'datetime:Y-m-d h:i A',
        ];
    }

    // returns the parent of the family
    public function parent()
    {
        return $this->patient->parent;
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function appt_type(): BelongsTo
    {
        return $this->belongsTo(ApptType::class);
    }

    public function appt_status(): BelongsTo
    {
        return $this->belongsTo(ApptStatus::class);
    }

    public function family()
    {
        return $this->patient->family;
    }

    public function patient_name()
    {
        $fullName = $this->patient()->get('first').' '.$this->patient()->get('last');

        return $fullName;
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'patient_id', 'patient_id')->orderBy('pmt_duedate', 'desc');

    }

    public function appts(): HasMany
    {
        return $this->hasMany(Appt::class, 'patient_id', 'patient_id')->orderBy('appt_date', 'desc');
    }

    public function hasFutureAppt()
    {
        return Appt::whereDate('appt_date', '>', new Carbon('today'))
            ->where(['patient_id' => $this->patient_id, 'appt_status_id' => 2])
            ->orderBy('appt_date', 'asc')
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

    public function scopeApptDate(Builder $query, $appt_date)
    {
        $query->where('appt_date', '=', $appt_date);
    }

    public function scopeApptTime(Builder $query, $appt_time)
    {
        $query->where('appt_time', '=', $appt_time->format('h:i:s'));
    }

    public function scopeScheduled(Builder $query)
    {
        $query->whereIn('appt_status_id', [1, 2, 7]);
    }

    public function scopeFam(Builder $query, $family)
    {
        $query->whereIn('patient_id', $family);
    }

    public function scopeBookedAppts(Builder $query)
    {
        $query->where('appt_status_id', 2);
    }

    public function scopeFamAppts(Builder $query, Patient $patient)
    {
        $query->whereIn('patient_id', $patient->family->modelKeys())->orderByDesc('id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($appointment) {
            if ($appointment->date_time == null) {
                $appointment->date_time = $appointment->appt_date->format('Y-m-d').' '.$appointment->appt_time('H:i:s');
                $appointment->save();
            }

        });
    }
}
