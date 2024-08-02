<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Setting
 *
 * @property int $id
 * @property int $days_in_week
 * @property int $weeks_visible
 * @package App\Models
 * @property string|null $alert_homepage
 * @property string|null $alert_sitewide
 * @property string|null $alert_calendar
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereCreatedAt($value)
 * @method static Builder|Setting whereUpdatedAt($value)
 * @method static Builder|Setting whereDaysInWeek($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereWeeksVisible($value)
 * @method static Builder|Setting whereAlertHomepage($value)
 * @method static Builder|Setting whereAlertSitewide($value)
 * @method static Builder|Setting whereAlertCalendar($value)
 * @method static Builder|Setting whereType($value)
 * @mixin \Eloquent
 */


class Setting extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'setting';
    public $timestamps = true;


    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'days_in_week',
        'weeks_visible',
        'alert_homepage',
        'alert_sitewide',
        'alert_calendar',
        'type'
    ];

}
