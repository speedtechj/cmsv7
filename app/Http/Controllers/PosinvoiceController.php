<?php

namespace App\Http\Controllers;

use App\Models\Branchcode;
use App\Models\Posinvoice;
use App\Models\Companyinfo;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class PosinvoiceController extends Controller
{
    public function posinvoice(Posinvoice $record){
        $storecode = Branchcode::latest()->first();
        $company = Companyinfo::latest()->first();
        $data['company'] = $company;
        $data['storecode'] = $storecode;
        $data['record'] = $record;
        $pdf = PDF::loadView("pdf.posinvoice", $data);
        return $pdf->inline();
        // //return view('barcode');
    }
}
