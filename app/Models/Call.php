<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Call
 *
 * @property int $id
 * @property Carbon $date
 * @property string|null $note
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $name
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Call newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Call newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Call query()
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call wherePhone($value)
 * @mixin \Eloquent
 */
class Call extends Model
{
    use SoftDeletes;
	protected $table = 'calls';
	protected $primaryKey = 'id';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'date',
		'note',
		'phone',
		'email',
		'name'
	];
}
