<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Companyinfo;
use App\Models\Packinglist;
use App\Models\Paymenttype;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class InvoiceController extends Controller
{
    public function invoice(Booking $record)
    {
        $companyinfo = Companyinfo::all()->first();
        $packinglistdata = Packinglist::where('booking_id', $record->id)->first();
        if ($packinglistdata == null) {
           $data['count'] = 0;
        } else {
            $data['count'] = collect($packinglistdata->packlistitem)->count();
        }
        $data['record'] = $record;
        $data['packinglist'] = $packinglistdata->packlistitem ?? null;
        $data['companyinfo'] = $companyinfo;
        $data['paymenttype'] = Paymenttype::all();
        $pdf = PDF::loadView("pdf.invoice", $data);
        $pdf->setOption('margin-top', '5mm');
        $pdf->setOption('margin-bottom', '5mm');
        $pdf->setOption('margin-right', '5mm');
        $pdf->setOption('margin-left', '5mm');
        return $pdf->inline();

    }
}
