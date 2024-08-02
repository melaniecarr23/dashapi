<?php

namespace App\Http\Requests;

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\OfficehoursController;
use App\Http\Controllers\OhController;
use App\Http\Controllers\PatientsController;
use App\Models\Appt;
use App\Models\Officehour;
use App\Models\Patient;
use App\Models\Plan;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
//use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use stdClass;
use Illuminate\Database\Eloquent\Collection;

class NewbieApptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array(Auth::user()->id,[1,2]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            "patient" => 'required',
            "doctor_id" => "numeric|required",
            "appt_reminder" => "required|nullable",
            "reminder_cell" => 'numeric|nullable',
            "note" => 'string|nullable',
            'type_id' => 'required|numeric',
            'status_id' => 'required|numeric',
            'date_time' => 'required',
            'appt_time' => 'required',
            'officehour_id' => 'required',
            'available.0' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return
            [
                'type_id' => 'Appt Type required',
                'status_id' => 'Appt Status required',
                'patient' => 'We need your name and contact info.',
                'officehour_id' => 'Invalid office hours.',
                'doctor_id' => 'Need a doc to schedule.',
                "appt_reminder" => "Appt Reminder? Yes or No",
                'date_time' => 'Appt Date Required',
                'appt_time' => 'Appt Time Required',
                'available.0' => 'Someone booked this online.  Try a different time.'
            ];
    }

    /**
     * Prepare the data for validation.
     * Does patient exist?
     * Does time booked match appt type?
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $date_time_time = new Carbon($this->date_time . ' '.$this->appt_time);
        $oh = (new OhController)->getTodaysSessions($date_time_time, $this->officehour_id)->first();
        // filter slots to start at appt_time
        $slots = array_filter($oh->slots[4], function($time) use ($date_time_time) {
            return $time->gte($date_time_time);
        });
        //create the new patient
        $req = $this->only(['first','last','home','cell','doctor_id','parent_id']);
        $patient = (new PatientsController)->getNewPatient($req);


        $this->merge([
            'doctor_id' => $this->doctor_id ?: Auth::user()->id,
            'appt_carbon' => $date_time_time,
            'appt_reminder' => $this->appt_reminder ? 1 : 0,
            'patient' => $patient,
            'note' => $patient->first.' '.$patient->last . ' '. $this->note,
            'type_id' => 4,
            'status_id' => 2,
            'available' => $slots
        ]);
//        dd($this->all());
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation(){
        // get patients
        $this->except(['first','last','cell','home','patient_id','parent_id','override','block','appt_carbon']);

    }


}
