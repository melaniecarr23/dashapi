<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Exam
 *
 * @property int $id
 * @property string|null $exam_name
 * @property int|null $exam_type_id
 * @property int|null $region_id
 * @property string|null $description
 * @property string|null $positive
 * @property string|null $indicates
 * @property string|null $confirmationtests
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Exam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exam query()
 * @mixin \Eloquent
 */
class Exam extends Model
{
    use SoftDeletes;
	protected $table = 'exam_list';

	protected $casts = [
		'exam_type_id' => 'int',
		'region_id' => 'int'
	];

	protected $fillable = [
		'exam_name',
		'exam_type_id',
		'region_id',
		'description',
		'positive',
		'indicates',
		'confirmationtests'
	];
}
