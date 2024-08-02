<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Twilio
 *
 * @property int $id
 * @property string $message
 * @property string $greeting
 * @property string $sender
 * @property string $status
 * @property string $number
 * @property string $nickname
 * @property int $patient_id
 * @property int $appt_id
 * @property string $sid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @package App\Models
 * @method static Builder|Twilio newModelQuery()
 * @method static Builder|Twilio newQuery()
 * @method static Builder|Twilio query()
 * @method static Builder|Twilio whereMessage($value)
 * @method static Builder|Twilio whereGreeting($value)
 * @method static Builder|Twilio whereSender($value)
 * @method static Builder|Twilio whereStatus($value)
 * @method static Builder|Twilio whereNumber($value)
 * @method static Builder|Twilio whereNickname($value)
 * @method static Builder|Twilio whereCreatedAt($value)
 * @method static Builder|Twilio whereUpdatedAt($value)
 * @method static Builder|Twilio whereId($value)
 * @method static Builder|Twilio whereSid($value)
 * @mixin Eloquent
 */
class Twilio extends Model
{
	protected $table = 'twilio';
	public $primaryKey = 'id';
	public $timestamps = true;


	protected $dates = [
		'created_at',
        'updated_at'
	];

	protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A'
    ];

	protected $fillable = [
		'message',
		'number',
        'nickname',
        'sender',
        'status',
        'greeting',
        'patient_id',
        'appt_id',
        'sid'
	];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function appt()
    {
        return $this->belongsTo(Appt::class, 'appt_id');
    }
}
