<?php

namespace App\Models;

use App\Models\FaqCategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FaqController
 *
 * @property int $id
 * @property string|null $slug
 * @property string $faq_question
 * @property string $faq_answer
 * @property int|null $faq_category_id
 * @property bool|null $faq_is_featured
 * @property int|null $faq_weight
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *  * @property FaqCategory $faq_category
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereFaqAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereFaqCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereFaqIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereFaqQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereFaqWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Faq extends Model
{
    use SoftDeletes;
	protected $table = 'faq';

	protected $casts = [
		'faq_category_id' => 'int',
		'faq_is_featured' => 'bool',
		'faq_weight' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'slug',
		'faq_question',
		'faq_answer',
		'faq_category_id',
		'faq_is_featured',
		'faq_weight',
		'created_by',
		'updated_by'
	];

    public function faq_category() {
        return $this->belongsTo(FaqCategory::class);
    }

}
