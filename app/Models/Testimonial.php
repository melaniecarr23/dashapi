<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Testimonial
 *
 * @property int $id
 * @property string $name
 * @property string $reviewtext
 * @property int $stars
 * @property string|null $title
 * @property Carbon|null $created_at
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial query()
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial whereReviewtext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial whereStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Testimonial whereTitle($value)
 * @mixin \Eloquent
 */
class Testimonial extends Model
{
    use SoftDeletes;
	protected $table = 'testimonials';

	protected $casts = [
		'stars' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'name',
		'reviewtext',
		'stars',
		'title',
		'created_at'
	];
}
