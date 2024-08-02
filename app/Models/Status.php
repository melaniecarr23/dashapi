<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApptStatus
 *
 * @property int $id
 * @property string|null $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ApptStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptStatus whereStatus($value)
 *
 * @mixin \Eloquent
 */
class ApptStatus extends Model
{
    use SoftDeletes;

    protected $table = 'appt_status';

    protected $fillable = [
        'appointment_status',
    ];
}
