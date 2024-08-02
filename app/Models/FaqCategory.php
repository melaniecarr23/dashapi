<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FaqCategory
 *
 * @property int $id
 * @property string|null $faq_category_name
 * @property int|null $faq_category_weight
 * @property bool|null $faq_category_is_featured
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereFaqCategoryIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereFaqCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereFaqCategoryWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqCategory whereId($value)
 * @mixin \Eloquent
 */
class FaqCategory extends Model
{
    use SoftDeletes;
	protected $table = 'faq_category';

	protected $casts = [
		'faq_category_weight' => 'int',
		'faq_category_is_featured' => 'bool'
	];

	protected $fillable = [
		'faq_category_name',
		'faq_category_weight',
		'faq_category_is_featured'
	];
}
