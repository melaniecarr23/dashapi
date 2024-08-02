<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Greeting extends Model
    /**
     * Class Greeting
     *
     * @package App\Models
     * @property int $id
     * @property int $active
     * @property string $message
     * @property int $greeting_type_id
     *  * @property GreetingType $greeting_type
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @method static Builder|Greeting newModelQuery()
     * @method static Builder|Greeting newQuery()
     * @method static Builder|Greeting query()
     * @method static Builder|Greeting whereCreatedAt($value)
     * @method static Builder|Greeting whereUpdatedAt($value)
     * @method static Builder|Greeting whereActive($value)
     * @method static Builder|Greeting whereId($value)
     * @method static Builder|Greeting whereMessage($value)
     * @method static Builder|Greeting whereGreetingTypeId($value)
     * @mixin \Eloquent
     */

{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'greeting';
    public $timestamps = true;


    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'message',
        'greeting_type_id',
        'active'
    ];

    public function greeting_type()
    {
        return $this->belongsTo(GreetingType::class);
    }


}
