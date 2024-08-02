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

class ApptRequest extends FormRequest
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
//            "patient_id.0" => 'required_without:parent_id',
            "patients" => 'required',
            "doctor_id" => "numeric|required",
            "override" => "numeric|nullable",
            "note" => 'string|nullable',
            'type_id' => 'required|numeric',
            'status_id' => 'required|numeric',
            'date_time' => 'required',
            'officehour_id' => 'required_without:override',
            'block' => 'numeric|nullable'
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
                'patients' => 'Choose a patient or family.',
                'officehour_id' => 'Invalid office hours.',
                'doctor_id' => 'Need a doc to schedule.',
                "appt_reminder" => "Appt Reminder? Yes or No",
                'date_time' => 'Appt Date Required',
                'appt_time' => 'Appt Time Required',
                'officehours' => 'Officehours required',
                'is_closed' => 'The office is closed that day.',
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
        //handle the blocking of multiple timeslots
        if($this->block > 0) {
            $this->merge([
                'patient_id' => [Auth::user()->patient_id],
                'type_id' => 1,
                'status_id' => 1,
            ]);
        }
//
        // if single patient, make it a list

        $patients = $this->parent_id ?
            Patient::where('parent_id','=',$this->parent_id)->get() :
            Patient::whereIn('id',$this->patient_id)->get();

        // get today's hours for this doctor
        $oh = (new OhController)->getTodaysSessions($date_time_time, $this->officehour_id)->first();
        // filter slots to start at appt_time
        $slots = array_filter($oh->slots[2], function($time) use ($date_time_time) {
            return $time->gte($date_time_time);
        });

        $this->merge([
            'doctor_id' => $this->doctor_id ?: Auth::user()->id,
            'is_closed' => $oh->isClosed,
            'date_time' => $date_time_time,
            'appt_carbon' => $date_time_time,
            'appt_reminder' => $this->appt_reminder ? 1 : 0,
            'patient_id' => isset($patient) ? [$patient->id] : $this->patient_id,
            'patients' => $patients,
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
//        dd($this->all());
        $this->merge([
            'status_id' => $this->status_id == 1 ? 1 : 2
        ]);
    }


}
