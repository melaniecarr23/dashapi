<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table = 'expense';
    public $timestamps = true;


    protected $casts = [
        'due_amt' => 'float',
        'pmt_amt' => 'float',
        'expense_category_id' => 'int',
        'invoice_id'=>'int',
        'type'=>'string'
    ];

    protected $dates = [
        'due_date',
        'paid_date'
    ];

    protected $fillable = [
        'name',
        'expense_category_id',
        'doctor_id',
        'description',
        'due_amt',
        'due_date',
        'pmt_amt',
        'paid_date',
        'pmt_type',
        'note',
        'invoice_id',
        'type'
    ];

    public function getDates()
    {
        return [
            'paid_date',
            'due_date',
            'created_at',
            'updated_at',
        ];
    }
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id','id');
    }

    public function invoice() {
        return $this->BelongsTo(Invoice::class, 'id','invoice_id');
    }

}
