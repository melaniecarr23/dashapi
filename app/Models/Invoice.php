<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'invoice';
    public $timestamps = true;

    protected $casts = [
        'payer_id' => 'int',
        'payee_id'=> 'int',
        'expense_total'=>'double',
        'credits_total'=>'double',
        'total_due'=>'double',
        'paid'=>'bool',
        'pmt_type'=>'string',
        'pmt_amt'=> 'double'

    ];

    protected $dates = [
        'paid_date',
        'deleted_at'
    ];

    protected $fillable = [
        'payer_id',
        'payee_id',
        'expense_total',
        'credits_total',
        'total_due',
        'paid',
        'pmt_type',
        'pmt_amt',
        'paid_date'
    ];

    public function getDates(): array
    {
        return [
            'created_at',
            'updated_at',
        ];
    }

    public function credits() {
        return $this->hasMany(Expense::class, 'invoice_id','id')
            ->whereRelation('type','credit')->get();
    }

    public function debits() {
        return $this->hasMany(Expense::class, 'invoice_id','id')
            ->whereRelation('type','debit')->get();
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class,'payer_id','id');
    }

    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class,'payee_id','id');
    }

    public function items(): HasMany{
        return $this->hasMany(InvoiceItem::class,'invoice_id','id');
    }

}
