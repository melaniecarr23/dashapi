<?php

namespace App\Http\Requests;

use App\Http\Controllers\MessagingController;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExportPayments extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->doctor_id == Auth::user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return  [
            'doctor_id' => 'required|numeric',
            'paid_start_date' => 'required',
            'paid_end_date' => 'required',
        ];
    }

    protected function passedValidation(){
        $start = Carbon::parse($this->paid_start_date)->startOfDay();
        $end = Carbon::parse($this->paid_end_date)->endOfDay();


        $this->merge([
            'paid_start_date' => $start,
            'paid_end_date' => $end,
        ]);
    }
}
