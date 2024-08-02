<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Plan
 *
 * @property int $id
 * @property string|null $plan
 * @property float|null $amount
 * @property int|null $member_limit
 * @property float|null $additional_member_cost
 * @property int $doctor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @package App\Models
 * @method static Builder|Plan newModelQuery()
 * @method static Builder|Plan newQuery()
 * @method static Builder|Plan query()
 * @method static Builder|Plan whereId($value)
 * @method static Builder|Plan wherePlan($value)
 * @method static Builder|Plan whereAmount($value)
 * @method static Builder|Plan whereMemberLimit($value)
 * @method static Builder|Plan whereAdditionalMemberCost($value)
 * @method static Builder|Plan whereDoctorId($value)
 * @mixin Eloquent
 */
class Plan extends Model
{
    use SoftDeletes;
	protected $table = 'plan';
    public $timestamps = true;

	protected $casts = [
		'planamt' => 'float'
	];

    protected $fillable = [
        'name',
        'amount',
        'member_limit',
        'additional_member_cost',
        'doctor_id'
    ];

    public function patients() {
        return $this->hasMany(Patient::class);
    }

    public function doctor() {
        return $this->belongsTo(User::class,'doctor_id','id');
    }

}
