<?php

namespace App\Http\Controllers;

use App\Models\Companyinfo;
use App\Models\Consignee;
use App\Models\Notifyparty;
use Illuminate\Http\Request;
use App\Models\Shippingbooking;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class Shippinginstruction extends Controller
{
    public function instruction(Shippingbooking $record)
    {

        $data['record'] = $record;
        $containerecord = $record->shippingcontainer;

       
        $companyinfo = Companyinfo::all()->first();
        $consignee = Consignee::where('is_current', true)->first();
        $notifyparty = Notifyparty::where('is_current', true)->first();
        $data['containerecord'] = $containerecord;
        $data['notifyparty'] = $notifyparty;
        $data['consignee'] = $consignee;
        $data['companyinfo'] = $companyinfo;
        // dd($data);
        $pdf = PDF::loadView("pdf.shippinginstruction", $data);
        return $pdf->inline();

    }
}
