<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Scan
 *
 * @property string $scan_abbr
 * @property int $id
 * @property string $scantype_name
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Scan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Scan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scan whereScanAbbr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scan whereScantypeName($value)
 * @mixin \Eloquent
 */
class Scan extends Model
{
    use SoftDeletes;
	protected $table = 'scan';
	protected $primaryKey = 'id';

	protected $fillable = [
		'scan_abbr',
		'scantype_name'
	];
}
