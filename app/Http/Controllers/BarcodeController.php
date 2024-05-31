<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Companyinfo;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
class BarcodeController extends Controller
{
    public function barcode(Booking $record){

        $companyinfo = Companyinfo::all()->first();
        $data['companyinfo'] = $companyinfo;
        $data['record'] = $record;
        $pdf = PDF::loadView("pdf.barcode", $data);
        return $pdf->inline();
        //return view('barcode');
    }
}
