<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ExamType
 *
 * @property int $id
 * @property string|null $exam_type
 * @package App\Models
 * @property int $ID
 * @method static \Illuminate\Database\Eloquent\Builder|ExamType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamType whereExamType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamType whereID($value)
 * @mixin \Eloquent
 */
class ExamType extends Model
{
    use SoftDeletes;
	protected $table = 'exam_type';

	protected $fillable = [
		'exam_type'
	];
}
