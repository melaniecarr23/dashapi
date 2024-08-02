<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ArchivePayment
 *
 * @property int $id
 * @property string|null $pmt_type
 * @property Carbon $pmt_duedate
 * @property float $due_amt
 * @property string|null $pmt_note
 * @property bool|null $pmt_paid
 * @property float|null $pmt_amt
 * @property Carbon|null $paid_date
 * @property float $writeoff
 * @property float $balance_due
 * @property int $patient_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Patient $patient
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereBalanceDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDueAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaidDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePmtAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePmtDuedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePmtNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePmtPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePmtType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereWriteoff($value)
 * @mixin \Eloquent
 */
class ArchivePayment extends Model
{
    use SoftDeletes;
	protected $table = 'archive_payment';
    public $timestamps = true;


    protected $casts = [
		'due_amt' => 'float',
		'pmt_paid' => 'bool',
		'pmt_amt' => 'float',
		'patient_id' => 'int',
        'balance_due' => 'int',
        'writeoff' => 'int'
	];

	protected $dates = [
		'pmt_duedate',
		'paid_date'
	];

	protected $fillable = [
		'pmt_type',
		'pmt_duedate',
		'due_amt',
		'pmt_note',
		'pmt_paid',
		'pmt_amt',
		'paid_date',
		'patient_id',
        'writeoff',
        'balance_due'
	];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function family() {
        return $this->patient->family->family;
    }

}
