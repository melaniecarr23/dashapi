<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArchiveAppt
 *
 * @property int $id
 * @property int|null $patient_id
 * @property Carbon|null $date_time
 * @property Carbon|null $appt_time
 * @property string|null $note
 * @property bool|null $appt_reminder
 * @property string|null $reminder_cell
 * @property Carbon|null $created_at
 * @property int|null $type_id
 * @property int|null $status_id
 * @property string|null $subjective
 * @property string|null $assessment
 * @property string|null $plan
 * @property string $clearUC
 * @property Carbon|null $updated_at
 * @property Patient $patient
 * @property ApptType $type
 * @package App\Models
 * @property-read \App\Models\ApptStatus|null $status
 * @property-read \App\Models\Family|null $family
 * @method static \Illuminate\Database\Eloquent\Builder|Appt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptReminder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereApptTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereClearUC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereReminderCell($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereSubjective($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArchiveAppt extends Model
{
    use SoftDeletes;
	protected $table = 'archive_appt';
	public $primaryKey = 'id';
    public $timestamps = true;


    protected $casts = [
		'patient_id' => 'int',
		'appt_reminder' => 'bool',
		'type_id' => 'int',
		'status_id' => 'int',
        'date_time' => 'datetime:Y-m-d',
        'appt_time' => 'datetime:h:i A'
	];

	protected $dates = [
		'date_time',
        'appt_time',
		'created_at',
		'updated_at'
	];

	protected $fillable = [
		'patient_id',
		'date_time',
		'appt_time',
		'note',
		'appt_reminder',
		'reminder_cell',
		'type_id',
		'status_id',
		'subjective',
		'assessment',
		'plan',
		'clearUC',
		'updated_at'
	];

	public function patient()
	{
		return $this->belongsTo(Patient::class, 'patient_id');
	}

	public function type()
	{
		return $this->belongsTo(ApptType::class);
	}

	public function status() {
	    return $this->belongsTo(ApptStatus::class);
    }

    public function family() {
        return $this->patient->family->family;
    }

    public function patient_name() {
	    return $this->patient()->nickname;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'patient_id','patient_id');

    }


}
