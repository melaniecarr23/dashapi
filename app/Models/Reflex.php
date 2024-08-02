<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reflex
 *
 * @property int $id
 * @property bool|null $grade
 * @property string|null $description
 * @property string|null $note
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Reflex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reflex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reflex query()
 * @mixin \Eloquent
 */
class Reflex extends Model
{
	protected $table = 'wexler_scale_reflex';

	protected $casts = [
		'grade' => 'bool'
	];

	protected $fillable = [
		'grade',
		'description',
		'note'
	];
}
