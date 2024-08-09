<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pos Invoice</title>


    <style>
        
        .logo {
            width: 200px;
            height: 60px;
            margin-bottom: 20px;
            
        }
        .invoice_title {
            font-size: 25px;
            
        }
        .info {
            border-collapse: collapse;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
            
           
        }
        .info td {
           font-size: 18px;
           font-family: Arial, Helvetica, sans-serif;
           
        }
        .order_date {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .items {
            
            border-collapse: collapse;
            margin-top: 5px;
           
        }
        .title_item = {
            font-size: 18px;
            font-weight: bold;
        }
        .items td,th{
            border: 1px solid gray;
            padding: 5px;
        }
        .items td {
            text-align: center;
            font-size: 1p5x;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
        }
        .items th {
            font-size: 18px;
        }
        .info td p {
            font-size: 18px;
            margin:0px;
        font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        .container {
            display:block;
            width: 100%;
            height: 650px;
        }
        .footer_add  {
            text-align: center;
            margin-top: 10px;
        }
    </style>

</head>

<body>
    <div class="container">
        <img class="logo" src="{{ public_path('storage/logo/logo.png') }}" alt="logo" />
       
        <table width="100%" class="info">
            <td>
                {{$company->company_address}}<br>
                {{$company->company_phone}}<br>
                {{$company->company_website}}
    
            </td>
            <td>
                {{'Invoice #:' . ' '.$storecode->storecode . $record->invoice_no }}<br>
                {{ 'Date:'. ' ' .$record->purchaseitems->first()->order_date }}<br><br>
            </td>
           
            </table>
            <br>
            <table width="100%" class="info">
                <td width="12%"><span>Sold To:</span><br><br><br></td>
            <td>
                {{$record->senders->full_name}}<br>
                {{$record->senderaddress->address}}
                {{$record->senderaddress->citycan->name}}
                {{$record->senderaddress->provincecan->name}}
                {{$record->senderaddress->postal_code}}
                <br>
                {{$record->senders->mobile_no}}<br>
              
            
            </td>
           
        
        
        </table>
        
        {{-- <div style="font-size: 18px; font-weight:bold; margin-top:20px">Items:</div> --}}
        <br><br>
        <table width="100%" class="items">
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            @php
             
                $subtotal = 0;
               
            @endphp
            @php
                $price = 0;
            @endphp
            @foreach ($record->purchaseitems as $item)
                @php
                        $delivery_charge = $item->agent_id < 1 ? 0 : $item->boxtype->delivery_charge;
    
                @endphp
            <tr>
                <td>{{ $item->boxtype->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{'$'.$item->boxtype->price + $delivery_charge}}</td>
                <td>{{'$'.$item->quantity  * ($delivery_charge + $item->boxtype->price) }}</td>
            </tr>
                @php
                    $subtotal = $subtotal + $item->quantity * ($delivery_charge + $item->boxtype->price)
                    
                @endphp
            @endforeach
                <tr>
                <td colspan="3" style="text-align: right">Sub Total</td>
                <td>
                    {{'$'. number_format($subtotal,2)}}
                </td>
                </tr>
               
               <tr>
                <td colspan="3" style="text-align: right">Discount</td>
                <td>
                       {{'('.'$'. number_format($record->purchaseitems->sum('total_discount'),2).')' ?? '-'}}
                </td>
               
        <tr>
             
                
                <td  style="text-align: right">Tax Included</td>
                <td>

                    {{ '$'. number_format($subtotal - $subtotal / (1 + ($record->senderaddress->provincecan->gst + $record->senderaddress->provincecan->pst + $record->senderaddress->provincecan->hst) / 100),2)}}
                  
                       
                </td>
                <td style="text-align: right">Amount Due</td>
            <td>
                @if (!$record->purchaseitems->sum('total_discount'))
                {{'$'. number_format($subtotal,2)}}
            @else
                {{'$'. number_format($subtotal - $record->purchaseitems->sum('total_discount'),2)}}
            @endif
                
            </td>
                 
            </tr>
                
        </table>
        
        <p class="footer_add">For Direct Flight Booking Visit <span style="text-decoration: underline">WWW.FOREXTRAVELDEALS.COM<span></p>
</div>
   
       {{-- end line --}}
       {{-- secodnd line --}}
      
        {{-- first line --}}
        <img class="logo" src="{{ public_path('storage/logo/logo.png') }}" alt="logo" />
       
        <table width="100%" class="info">
            <td>
                {{$company->company_address}}<br>
                {{$company->company_phone}}<br>
                {{$company->company_website}}
    
            </td>
            <td>
                {{'Invoice #:' . ' '.$storecode->storecode . $record->invoice_no }}<br>
                {{ 'Date:'. ' ' .$record->purchaseitems->first()->order_date }}<br><br>
            </td>
           
            </table>
            <br>
            <table width="100%" class="info">
                <td width="12%"><span>Sold To:</span><br><br><br></td>
            <td>
                {{$record->senders->full_name}}<br>
                {{$record->senderaddress->address}}
                {{$record->senderaddress->citycan->name}}
                {{$record->senderaddress->provincecan->name}}
                {{$record->senderaddress->postal_code}}
                <br>
                {{$record->senders->mobile_no}}<br>
              
            
            </td>
           
        
        
        </table>
        
        {{-- <div style="font-size: 18px; font-weight:bold; margin-top:20px">Items:</div> --}}
        <br><br>
        <table width="100%" class="items">
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            @php
             
                $subtotal = 0;
               
            @endphp
            @php
                $price = 0;
            @endphp
            @foreach ($record->purchaseitems as $item)
                @php
                        $delivery_charge = $item->agent_id < 1 ? 0 : $item->boxtype->delivery_charge;
    
                @endphp
            <tr>
                <td>{{ $item->boxtype->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{'$'.$item->boxtype->price + $delivery_charge}}</td>
                <td>{{'$'.$item->quantity  * ($delivery_charge + $item->boxtype->price) }}</td>
            </tr>
                @php
                     $subtotal = $subtotal + $item->quantity * ($delivery_charge + $item->boxtype->price)
                    
                @endphp
            @endforeach
                <tr>
                <td colspan="3" style="text-align: right">Sub Total</td>
                <td>
                    {{'$'. number_format($subtotal,2)}}
                </td>
                </tr>
               
               <tr>
                <td colspan="3" style="text-align: right">Discount</td>
                <td>
                       {{'('.'$'. number_format($record->purchaseitems->sum('total_discount'),2).')' ?? '-'}}
                </td>
               
        <tr>
             
                
                <td  style="text-align: right">Tax Included</td>
                <td>
                   
                    {{ '$'. number_format($subtotal - $subtotal / (1 + ($record->senderaddress->provincecan->gst + $record->senderaddress->provincecan->pst + $record->senderaddress->provincecan->hst) / 100),2)}}
                       
                </td>
                <td style="text-align: right">Amount Due</td>
            <td>
                @if (!$record->purchaseitems->sum('total_discount'))
                {{'$'. number_format($subtotal,2)}}
            @else
                {{'$'. number_format($subtotal - $record->purchaseitems->sum('total_discount'),2)}}
            @endif
                
            </td>
                 
            </tr>
                
        </table>
       
        <p class="footer_add">For Direct Flight Booking Visit <span style="text-decoration: underline">WWW.FOREXTRAVELDEALS.COM<span></p>
          {{-- end line --}}
</body>

</html>
