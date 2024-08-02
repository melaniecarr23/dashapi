<?php

namespace App\Http\Requests;

use App\Http\Controllers\MessagingController;
use App\Models\Patient;
use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PatientRequest extends FormRequest
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
                'first' => 'required',
                'last' => 'required',
                'cell' => 'nullable|digits:10|numeric|required_without_all:home',
                'home' => 'nullable|digits:10|numeric|required_without_all:cell',
                'email' => 'nullable|email'
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
                'first' => 'First name required',
                'last' => 'Last name required.',
                'digits:10' => 'The number must be 10 digits.',
                'email' => 'Check your email address.  It must be real!',
                'required_without_all' => 'We need to be able to call or text, so home or cell is required.'
            ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
//            'active' => $this->active ? 1 : 0
        ]);
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(){
        // default to BOTW for new patients

        $this->merge([
            'first' => Str::ucfirst($this->first),
            'last' => Str::ucfirst($this->last),
            'active' => $this->active ? 1 : 0,
            'cell' => (new MessagingController)->validateTel($this->cell) ?: null,
            'home' => (new MessagingController)->validateTel($this->home) ?: null,
            'nickname'=> $this->nickname ?: $this->getNickname($this->first, $this->last),
            'plan_id' => $this->plan_id ?: $this->getDefaultPlan(),
            'doctor_id' => $this->doctor_id ?: Auth::user()->id,
            'payment_day' => $this->payment_day ?: $this->getDefaultPayday()
        ]);
    }

    public function getNickname($first, $last) {
        $this->patients = Patient::where('doctor_id','=',Auth::user()->id)->orWhere('secondary_id','=',Auth::user()->id)->pluck('nickname','id');
        $z = 3;
        $y = 1;
        do {
            $l = ucfirst(substr($last, 0,$z));
            $f = ucfirst(substr($first,0, $y));
            $nickname = $l.$f;
            $y++;
        } while (in_array($nickname, $this->patients->toArray()));
        return $nickname;
    }

    public function getDefaultPlan() {
        $plan = Plan::whereDoctorId(Auth::user()->id)->where('plan','=','BOTW')->first();
        return $plan->id;
    }

    public function getDefaultPayday() {
        // if greater than the 15th
        return Carbon::now()->between(Carbon::now()->day(2), Carbon::now()->day(15)) ? 15 : 1;
    }
}
