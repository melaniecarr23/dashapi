<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Muscletest
 *
 * @property int $id
 * @property bool|null $muscle_grade
 * @property string|null $description
 * @property string|null $note
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Muscletest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Muscletest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Muscletest query()
 * @method static \Illuminate\Database\Eloquent\Builder|Muscletest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Muscletest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Muscletest whereMuscleGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Muscletest whereNote($value)
 * @mixin \Eloquent
 */
class Muscletest extends Model
{
	protected $table = 'muscletest';

	protected $casts = [
		'muscle_grade' => 'bool'
	];

	protected $fillable = [
		'muscle_grade',
		'description',
		'note'
	];
}
