<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AppointmentController extends Controller
{
    public function getTodayAppointments(){
        $data = DB::select('EXEC [dbo].[View_AppointmentsTodayNotCheckedIn_Proc]');
        return response()->json($data);
    }

    public function getCheckedAppointments(){
        $data = DB::select('EXEC [dbo].[View_AppointmentsCheckedIn_Proc]');
        return response()->json($data);
    }

    public function getUpComingAppointments(){
        $data = DB::select('EXEC [dbo].[View_AppointmentsUpcoming_Proc]');
        return response()->json($data);
    }  
    
    public function getFormCreateAppointments(){
        return view('createAppointment');
    }

    public function postCreateAppointments(Request $request){
        $values = [];
        $values = array(
                'sponsoringUserID' => $request->sponsoringUserID,
                'startDateTime' => $request->startDateTime,
                'endDateTime' => $request->endDateTime,
                'purpose' => $request->purpose,
                'appointmentRoomNumber' => $request->appointmentRoomNumber,
                'guestEmails' => array($request->guestEmails)
            );
        
        $values = json_encode($values);
        $data = DB::select('EXEC [dbo].[Create_Appointment_Proc] :AppointmentInfo', [$values]);
        // echo $values;
        return response()->json($data);
    }    
    
    public function postUpdateCheckIn(Request $request){

        $data = DB::select(DB::raw('EXEC [dbo].[Update_AppointmentCheckIn_Proc] :GuestID'),[
            ':GuestID' => $request->GuestID
        ]);

        return response()->json($data);
    } 
    
    public function postUpdateCheckOut(Request $request){

        $data = DB::select(DB::raw('EXEC [dbo].[Update_AppointmentCheckOut_Proc] :GuestID, :AppointmentID'),[
            ':AppointmentID' => $request->AppointmentID,
            ':GuestID' => $request->GuestID
        ]);

        return response()->json($data);
    }       
}
