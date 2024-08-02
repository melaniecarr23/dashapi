<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $page_id
 * @property string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Page $page
 * @property User $user
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use SoftDeletes;
	protected $table = 'comment';
	public bool $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'page_id' => 'int'
	];

	protected $dates = [
		'date_entered'
	];

	protected $fillable = [
		'user_id',
		'page_id',
		'comment',
		'date_entered'
	];

	public function page()
	{
		return $this->belongsTo(Page::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
