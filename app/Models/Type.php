<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Type
 *
 * @property int $id
 * @property string|null $appt_type_name
 * @property string|null $appt_abbr
 * @property float|null $appt_amt
 * @property string|null $appt_code
 * @property int $appt_type_length
 * @property Collection|Appt[] $appts
 * @property-read int|null $appts_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptTypeLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereApptTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApptType whereId($value)
 *
 * @mixin \Eloquent
 */
class Type extends Model
{
    use SoftDeletes;

    protected $table = 'type';

    protected $fillable = [
        'appt_type_name',
        'appt_abbr',
        'appt_amt',
        'appt_code',
        'appt_type_length',
    ];

    protected function casts(): array
    {
        return [
            'appt_amt' => 'float',
            'appt_type_length' => 'int',
        ];
    }

    public function appts(): HasMany
    {
        return $this->hasMany(Appt::class);
    }
}
