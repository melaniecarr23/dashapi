<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Profile
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $first
 * @property string|null $last
 * @property Carbon|null $dob
 * @property int $gender_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserId($value)
 * @mixin \Eloquent
 */
class Profile extends Model
{
    use SoftDeletes;
	protected $table = 'profile';

	protected $casts = [
		'user_id' => 'int',
		'gender_id' => 'int'
	];

	protected $dates = [
		'dob'
	];

	protected $fillable = [
		'user_id',
		'first',
		'last',
		'dob',
		'gender_id'
	];
}
