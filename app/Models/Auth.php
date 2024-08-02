<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Auth
 *
 * @property int $id
 * @property int $user_id
 * @property string $source
 * @property string $source_id
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Auth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth query()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth whereUserId($value)
 * @mixin \Eloquent
 */
class Auth extends Model
{
	protected $table = 'auth';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'source',
		'source_id'
	];
}
