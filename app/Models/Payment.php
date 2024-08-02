<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 *
 * @property int $id
 * @property int $patient_id
 * @property int $doctor_id
 * @property Carbon|null $date
 * @property Carbon|null $due_date
 * @property string|null $method
 * @property float|null $amount
 * @property string|null $status
 * @property string|null $note
 * @package App\Models
 */
class Payment extends Model
{
    protected $table = 'payment';
    public $timestamps = true;

    protected $casts = [
        'patient_id' => 'int',
        'doctor_id' => 'int',
        'amount' => 'float',
    ];

    protected $dates = [
        'date',
        'due_date'
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'due_date',
        'method',
        'amount',
        'status',
        'note'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
