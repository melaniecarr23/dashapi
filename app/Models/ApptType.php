<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApptType
 *
 * @property int $id
 * @property string|null $type_name
 * @property string|null $abbr
 * @property float|null $amount
 * @property string|null $code
 * @property int $type_length
 * @property Collection|Appt[] $appts
 * @package App\Models
 * @property-read int|null $appts_count
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptTypeLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereId($value)
 * @mixin \Eloquent
 */
class ApptType extends Model
{
    use SoftDeletes;
	protected $table = 'type';

	protected $casts = [
		'amount' => 'float',
		'type_length' => 'int'
	];

	protected $fillable = [
		'type_name',
		'abbr',
		'amount',
		'code',
		'type_length'
	];

	public function appts()
	{
		return $this->hasMany(Appt::class);
	}
}
