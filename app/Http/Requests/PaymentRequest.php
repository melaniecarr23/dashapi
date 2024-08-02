<?php

namespace App\Http\Requests;

use App\Http\Controllers\MessagingController;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only authorize Dr. Carr or Dr. Dave
        return in_array(Auth::user()->id,[1,2]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return  [
            'patient_id' => 'required|numeric',
            'pmt_amt' => 'nullable|numeric|min:0|required_without:due_amt',
            'pmt_type' => 'required_if:pmt_amt,>,1',
            'due_amt' => 'nullable|numeric|min:0|required_without:pmt_amt|required_with:pmt_duedate',
        ];

        //
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return
            [
                'patient_id' => 'Choose a patient!',
                'pmt_amt' => 'Payment must be numbers only',
                'due_amt' => 'Due amt must be numbers only.',
                'required_without' => 'Either an amount due or paid must be entered.',
                'pmt_type' => 'You forgot to mark what type of payment was made.',
                'required_with:pmt_duedate' => 'If entering a due date, you must enter the amount due.'
            ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
//        dd($this->all());
        // no due amt supplied but payment amt entered
        if($this->pmt_amt > 0 && $this->due_amt == 0) {
        $this->merge([
            'due_amt' => floatval($this->pmt_amt),
            'paid_date' => $this->paid_date ?: Carbon::now()->format('Y-m-d'),
            'pmt_due_date' => $this->pmt_duedate ?: Carbon::now()->format('Y-m-d')
        ]);
        } else {
            $this->merge([
                'due_amt' => floatval($this->due_amt),
                'pmt_amt' => floatval($this->pmt_amt ?: 0),
                'balance_due' => floatval($this->balance_due),
                'writeoff' => floatval($this->writeoff)
            ]);
        }

    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(){


        $balance_due = $this->due_amt - $this->pmt_amt - $this->writeoff;
        $this->merge([
            'patient_id' => $this->pmt_type == 'BOTW' ? Auth::user()->patient_id : $this->patient_id,
            'pmt_type' => $this->pmt_type == 'BOTW' ? 'Cash' : $this->pmt_type,
            'pmt_category' => $this->pmt_category ?: 'Services',
            'balance_due' => floatval($balance_due),
            'pmt_paid' => floatval($balance_due) < 0.01 ? 1 : 0,
        ]);
    }



}
