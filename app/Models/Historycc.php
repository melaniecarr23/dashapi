<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Historycc
 *
 * @property int $id
 * @property int|null $appt_id
 * @property Carbon|null $created_at
 * @property int|null $patient_id
 * @property string|null $chief_complaint
 * @property string|null $location
 * @property string|null $onset
 * @property string|null $mechanism
 * @property string|null $historyCC
 * @property string|null $before_details
 * @property string|null $quality
 * @property string|null $initial_rating
 * @property string|null $current_rating
 * @property string|null $severity
 * @property string|null $frequency
 * @property string|null $duration
 * @property string|null $better_worse
 * @property string|null $palleative
 * @property string|null $provocative
 * @property string|null $providers
 * @property string|null $treatment
 * @property string|null $ADLs
 * @property string|null $eADLs
 * @property string|null $prior_medical_dx
 * @property string|null $surgeries
 * @property string|null $medications
 * @property string|null $vaccinated
 * @property string|null $fatherPMH
 * @property string|null $motherPMH
 * @property string|null $siblingsPMH
 * @property string|null $childrenPMH
 * @property string|null $smoker
 * @property string|null $alcohol
 * @property string|null $recreational_drugs
 * @property string|null $supplements
 * @property string|null $diet
 * @property string|null $allergies
 * @property string|null $sleep
 * @property string|null $exercise
 * @property string|null $occupation
 * @property string|null $ros_head
 * @property string|null $ros_EENT
 * @property string|null $ros_cardiovascular
 * @property string|null $ros_respiratory
 * @property string|null $ros_GI
 * @property string|null $ros_GU
 * @property string|null $ros_musculoskeletal
 * @property string|null $ros_dematological
 * @property string|null $ros_neurgological
 * @property string|null $ros_psychiatric
 * @property string|null $ros_endocrine
 * @property string|null $ros_immune
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc query()
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereADLs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereAlcohol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereAllergies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereApptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereBeforeDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereBetterWorse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereChiefComplaint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereChildrenPMH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereCurrentRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereDiet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereEADLs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereExercise($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereFatherPMH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereHistoryCC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereInitialRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereMechanism($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereMedications($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereMotherPMH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereOnset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc wherePalleative($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc wherePriorMedicalDx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereProviders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereProvocative($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereQuality($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereROSHead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRecreationalDrugs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosCardiovascular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosDematological($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosEENT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosEndocrine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosGI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosGU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosImmune($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosMusculoskeletal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosNeurgological($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosPsychiatric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereRosRespiratory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereSiblingsPMH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereSleep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereSmoker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereSupplements($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereSurgeries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereTreatment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Historycc whereVaccinated($value)
 * @mixin \Eloquent
 */
class Historycc extends Model
{
	protected $table = 'historycc';

	protected  $casts = [
		'appt_id' => 'int',
		'patient_id' => 'int'
	];

	protected $dates = [
		'examtime'
	];

	protected $fillable = [
		'appt_id',
		'examtime',
		'patient_id',
		'chief_complaint',
		'location',
		'onset',
		'mechanism',
		'historyCC',
		'before_details',
		'quality',
		'initial_rating',
		'current_rating',
		'severity',
		'frequency',
		'duration',
		'better_worse',
		'palleative',
		'provocative',
		'providers',
		'treatment',
		'ADLs',
		'eADLs',
		'prior_medical_dx',
		'surgeries',
		'medications',
		'vaccinated',
		'fatherPMH',
		'motherPMh',
		'siblingsPMH',
		'childrenPMG',
		'smoker',
		'alcohol',
		'recreational_drugs',
		'supplements',
		'diet',
		'allergies',
		'sleep',
		'exercise',
		'occupation',
		'ROS_head',
		'ros_EENT',
		'ros_cardiovascular',
		'ros_respiratory',
		'ros_GI',
		'ros_GU',
		'ros_musculoskeletal',
		'ros_dematological',
		'ros_neurgological',
		'ros_psychiatric',
		'ros_endocrine',
		'ros_immune'
	];
}
