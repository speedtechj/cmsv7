<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Instruction</title>


    <style>
        .body {
            font-family: Arial, sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .title {
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            
        }

        .subtitle {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            text-decoration:underline;
            
        }

        .logo {
            width: 250px;
            height: 100px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 10px;
            text-align: center;
        }

        .table1 {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }

        .table1 td {
            width: 100%;
            border: 1px solid black;
            padding: 5px;

        }

        .table2 {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
            
        }
        

        .table2 td {
            width: 50%;
            border: 1px solid black;
            padding: 5px;

        }
        .table3 {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        .table3 th{
            border: 1px solid black;
            padding: 5px;
        }
        .table3 td{
            padding: 5px;
            border: 1px solid black;
            /* margin-top: 20px; */
            /* width: 100%; */
            /* border-collapse: collapse; */
        }
        .table4 {
            
            width: 100%;
            border-collapse: collapse;
        }

        .table4 td {
            width: 50%;
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            border-left: 1px solid black;

            padding: 5px;

        }
        .table4  p {
           font-size: 18px;
           font-weight: bold;
           margin: 5px;
            
        }
        .table1  p {
           font-size: 18px;
           font-weight: bold;
           margin: 5px;
            
        }
        h4 {
            margin: 2px;
        }
    </style>

</head>

<body>
    <img class="logo" src="{{ public_path('storage/logo/logo.png') }}" alt="logo" />
    <div class="title">SHIPPING INSTRUCTIONS</div>
    <div class="subtitle">Booking Reference:&nbsp;&nbsp;&nbsp;{{ $record->booking_no }}</div>
    <table class="table1">
        <tr>
            <td>
                <h4>SHIPPER:</h4>
                <P>{{$companyinfo->company_name}}</P>
                <p>{{$companyinfo->company_address}}</p>
                <p>Canada</p>
            </td>
        </tr>
    </table>
    <table class="table4">
        <tr>
            <td>
                <h4>CONSIGNEE:</h4>
                <p>{{$consignee->company_name}}
                <p>
                <p>{{$consignee->address}}
                <p>
                <p>{{$consignee->city}}&nbsp;{{$consignee->province}}&nbsp;{{$consignee->zip_code}}&nbsp;{{$consignee->country}}
                <p>
                <p>Contact Number: {{$consignee->contact_number}}</p>
                <p>Email: {{$consignee->email}}</p>
            </td>
       
            <td>
                <h4>NOTIFY PARTY:</h4>
                <p>{{$notifyparty->company_name}}
                <p>
                <p>{{$notifyparty->address}}
                <p>
                    <p>{{$notifyparty->city}}&nbsp;{{$notifyparty->province}}&nbsp;{{$notifyparty->zip_code}}&nbsp;{{$notifyparty->country}}
                        <p>
                            <p>Contact Number: {{$notifyparty->contact_number}}</p>
                            <p>Email: {{$notifyparty->email}}</p>
            </td>
        </tr>
    </table>
    <table class="table2">
        <tr>
            <td>ETD: {{$record->etd}}</td>
            <td>ETA: {{$record->eta}}</td>
        </tr>
        <tr>
            <td></td>
            <td>Place of Receipt:<br>{{$record->origin_terminal}}</td>
        </tr>
        <tr>
            <td>
                Ocean Vessel / Voyage Number:<br>
                {{$record->vessel}}
            </td>
            <td>
                Port of Loading:<br>
                {{$record->port_of_loading}}
            </td>
        </tr>
        <tr>
            <td>
                Port of Discharge:<br>
                {{$record->port_of_unloading}}
            </td>
            <td>
                Place of Delivery:<br>
               {{$record->place_of_receipt}}
            </td>
        </tr>
    </table>
    <table class="table3">
        <thead>
        <th>Container Number/(s)</th>
        <th>Seal Number/(s)</th>
        <th>Container</th>
        <th>Quantity</th>
        <th>Description of Goods</th>
        <th>Gross Weight</th>
        <th>Gross Measurement</th>
        </thead>
        <tbody>
           

            @foreach ($containerecord as $containerecords)
    
            <tr>
                <td >{{$containerecords->container_no}}</td>
                <td >{{$containerecords->seal_no}}</td>
                <td>{{$containerecords->equipment->code}}</td>
                <td>{{$containerecords->total_box}} boxes</td>
                <td>Consolidated {{$record->commodity}}.<br>
                    HS CODE: {{$record->hs_code}}</td>
                <td width="10%">{{number_format($containerecords->cargo_weight,0,",")}} lbs <br> /{{number_format($containerecords->cargo_weight * 0.45359237,0,",") }} kgs</td>
                <td>{{$containerecords->total_cbm}} cbm</td>
            </tr>

@endforeach

            
    </thead>
        <tfoot>
            <td style="font-size:15px;font-weight:bold">Total</td>
            <td></td>
            <td>{{$containerecord->count()}}</td>
            <td>{{$containerecord->sum('total_box')}} boxes</td>
            <td></td>
            <td>{{number_format($containerecord->sum('cargo_weight'),0,",")}} lbs / <br>
                {{number_format($containerecord->sum('cargo_weight') * 0.45359237 ,0,",")}} kgs
            </td>
            <td>{{$containerecord->sum('total_cbm')}} cbm</td>
        </tfoot>
    </table>
</body>

</html>
