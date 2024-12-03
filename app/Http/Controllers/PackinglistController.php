<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use data;
use App\Models\Booking;
use App\Models\Companyinfo;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PackinglistController extends Controller
{
    public function index(Booking $record){
        
        $companyinfo = Companyinfo::all()->first();
        // $packinglistdata = Packinglist::where('booking_id', $record->id)->get();
        $data['record'] = $record;
        $data['companyinfo'] = $companyinfo;
        // $data['packinglist'] = $packinglistdata;
        // $data['paymenttype'] = Paymenttype::all();
         
        $pdf = PDF::loadView("pdf.packinglist", $data);
        $pdf->setOption('margin-top','5mm');
        $pdf->setOption('margin-bottom','5mm');
        $pdf->setOption('margin-right','5mm');
        $pdf->setOption('margin-left','5mm');
         return $pdf->inline();
      
    }
}
