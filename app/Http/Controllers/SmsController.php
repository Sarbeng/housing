<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\WelcomeTenant;
use App\Models\Tenant; // Or Tenant model

class SmsController extends Controller
{


    /**
     * Display a an sms page for shege reasons.
     */
    public function send_sms()
    {
        //$payments = ServicePayment::orderBy('service_type', 'desc')->get();



    // Find the user you want to notify
    #$user = Tenant::find(1);

    // The message you want to send
    #$sms_content = "Your account has been successfully created and activated!";
    $tenant = Tenant::with(['rentalAgreement','unit'])->findorFail(1);
    $agreement = $tenant->rentalAgreement->first();

    # check if rent has expired or not
    if ($agreement->end_date->isPast()) {
        $rent_expiry = "your rent expired" . " " . $agreement->end_date->diffforhumans();
    } else {
        $rent_expiry = "your rent expires" . " " . $agreement->end_date->diffforhumans();
    }

    # full sms message
    $sms = "Dear" . " " . $agreement->tenant->name . " " . $rent_expiry . ". Please renew your rent on time to avoid inconveniences. Thank you!";


    // Send the notification!
    $tenant->notify(new WelcomeTenant($sms));

    #return response()->json(['message' =>  $send_message], 200);
        }



}
