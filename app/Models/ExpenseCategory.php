<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;
    protected $table = 'expense_category';


    protected $fillable = [
        'name',
        'description'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }



}
