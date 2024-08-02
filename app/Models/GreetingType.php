<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GreetingType extends Model
    /**
     * Class GreetingType
     *
     * @package App\Models
     * @property int $id
     * @property string|null $description
     * @property string $type
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @method static Builder|GreetingType newModelQuery()
     * @method static Builder|GreetingType newQuery()
     * @method static Builder|GreetingType query()
     * @method static Builder|GreetingType whereCreatedAt($value)
     * @method static Builder|GreetingType whereUpdatedAt($value)
     * @method static Builder|GreetingType whereId($value)
     * @method static Builder|GreetingType whereDescription($value)
     * @method static Builder|GreetingType whereType($value)
     * @mixin \Eloquent
     */

{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'greeting_type';
    public $timestamps = true;


    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'description',
        'type'
    ];

    public function greetings()
    {
        return $this->hasMany(Greeting::class);
    }


}
