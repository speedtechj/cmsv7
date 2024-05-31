<?php

use App\Http\Controllers\Railbill;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Shippinginstruction;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('{record}/barcode',[BarcodeController::class,'barcode'])->name('barcode1.pdf.download');
Route::get('{record}/pdf',[InvoiceController::class,'invoice'])->name('barcode.pdf.download');
Route::get('{record}/info',[Shippinginstruction::class,'instruction'])->name('instructionshipping');
Route::get('{record}/data',[Railbill::class,'railbillinfo'])->name('railbillinfo');
