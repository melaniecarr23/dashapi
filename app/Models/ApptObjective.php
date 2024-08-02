<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApptObjective
 *
 * @property int $id
 * @property int|null $appt_id
 * @property int|null $objective_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $finding
 * @property string $note
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective whereApptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective whereFinding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective whereObjectiveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptObjective whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApptObjective extends Model
{
    use SoftDeletes;
	protected $table = 'appt_objective';

	protected $casts = [
		'appt_id' => 'int',
		'objective_id' => 'int'
	];

	protected $fillable = [
		'appt_id',
		'objective_id',
        'finding',
        'note'
	];
}
