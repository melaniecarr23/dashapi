<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Appt;
use App\Models\ApptStatus;
use App\Models\ApptType;
use App\Models\Day;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\SessionHour;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $today = Carbon::today();
            $useStartOfWeek = $today->isWeekday() && !$today->isMonday();
            $startDate = $useStartOfWeek ? $today->startOfWeek() : $today;
            $endDate = Carbon::now()->addWeeks(5)->endOfWeek();

            $days = SessionHour::with(['appts.patient']) // Ensure the relationship names are correct
            ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get();

            // Group by week
            $groupedByWeek = $days->chunk(5);

            $payments = Payment::whereNull('paid')
                ->where('due_date', '>', Carbon::now()->subMonths(2))
                ->get();
            $appts = Appt::whereBetween('date_time',[$startDate,$endDate])->get();
            $status = ApptStatus::pluck('id','name');
            $type = ApptType::pluck('id','abbr');
            $patients = Patient::pluck('id','nickname');
            $plans = Plan::pluck('id','name');
//            dd($days, $groupedByWeek);
            return response()->json([
                'days_weekly'=> $groupedByWeek,
                'days' => $days,
                'appointments' => $appts,
                'payments' => $payments,
                'statuses' =>$status,
                'types' => $type,
                'patients' => $patients,
                'plans' => $plans
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function addBlankDays($session_hours)
    {

        // Check if the first day is Monday and add blank days if needed
        $firstDay = Carbon::parse($session_hours->first()->day);
        $firstDayOfWeek = $firstDay->format('l');
        $blanksNeeded = $firstDayOfWeek === 'Monday' ? 0 : ($firstDay->dayOfWeek - 1);

        for ($i = 0; $i < $blanksNeeded; $i++) {
            $blank = new SessionHour();
            $blank->day = $firstDay->copy()->subDays($blanksNeeded - $i)->format('Y-m-d');// Mark it as a blank day
            $session_hours->prepend($blank);
        }

        return $session_hours;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
