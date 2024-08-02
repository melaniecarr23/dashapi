<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Region
 *
 * @property int $id
 * @property string|null $region
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereRegion($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
	protected $table = 'region';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $fillable = [
		'region'
	];
}
