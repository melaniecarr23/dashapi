<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Objective
 *
 * @property int $id
 * @property string|null $objective
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Objective newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Objective newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Objective query()
 * @method static \Illuminate\Database\Eloquent\Builder|Objective whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Objective whereObjective($value)
 * @mixin \Eloquent
 */
class Objective extends Model
{
	protected $table = 'objective';

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'objective'
	];
}
