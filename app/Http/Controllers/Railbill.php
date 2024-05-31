<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shippingcontainer;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
class Railbill extends Controller
{
    public function  railbillinfo(Shippingcontainer $record)
    {
        $data['record'] = $record;
        // dd($record);
      
       
        // $companyinfo = Companyinfo::all()->first();
        // $consignee = Consignee::where('is_current', true)->first();
        // $notifyparty = Notifyparty::where('is_current', true)->first();
        // $data['containerecord'] = $containerecord;
        // $data['notifyparty'] = $notifyparty;
        // $data['consignee'] = $consignee;
        // $data['companyinfo'] = $companyinfo;
        // dd($data);
        $pdf = PDF::loadView("pdf.railbill", $data);
        return $pdf->inline();

    }
}
