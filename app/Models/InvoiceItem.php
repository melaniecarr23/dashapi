<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
class InvoiceItem extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'invoice_item';
    public $timestamps = true;


    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'amount' => 'double',
        'note'=>'string',
        'doctor_id'=>'int',
        'invoice_id'=>'int',
        'type'=>'string'
    ];

    protected $dates = [
///
    ];

    protected $fillable = [
        'name',
        'doctor_id',
        'description',
        'note',
        'amount',
        'invoice_id',
        'type'
    ];
    public function getDates()
    {
        return [
            'created_at',
            'updated_at',
        ];
    }

    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id','id');
    }

    public function invoice() {
        return $this->BelongsTo(Invoice::class,'id','invoice_id');
    }

}
