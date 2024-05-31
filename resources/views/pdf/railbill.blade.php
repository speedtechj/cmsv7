<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rail Bill Information</title>


    <style>
        .title {
            font-size: 30px;
            font-weight: bold;
            text-align: center;

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
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .table1 td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .table2 {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .table2 td {
            
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .side {
            width: 40%;
        }
        table td {
            font-size: 25px;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif'
        }
    </style>

</head>

<body>
    <img class="logo" src="{{ public_path('storage/logo/logo.png') }}" alt="logo" />
    <div class="title">RAIL BILL INFORMATION</div>
    <table class="table1">
        <td class="side">Trucker:</td>
        <td>{{$record->trucker->name}}</td>
    </table>
    <table class="table2">
        <tr>
            <td>Booking Reference:</td>
            <td>{{$record->shippingbooking->booking_no}}</td>
        </tr>
        <tr>
            <td class="side">Shipping Line:</td>
            <td>{{$record->shippingbooking->carrier->name}}</td>
        </tr>
        <tr>
            <td class="side">Ocean Vessel / Voyage:</td>
            <td>{{$record->shippingbooking->vessel}}</td>
        </tr>
        <tr>
            <td class="side">Equipment:</td>
            <td>{{$record->equipment->code}}</td>
        </tr>
        <tr>
            <td class="side">Container Number:</td>
            <td>{{$record->container_no}}</td>
        </tr>
        <tr>
            <td class="side">Seal Number:</td>
            <td>{{$record->seal_no}}</td>
        </tr>
        <tr>
            <td class="side">Cargo Weight:</td>
            <td>{{number_format($record->cargo_weight),0,","}} lbs /
                {{number_format($record->cargo_weight * 0.45359237),0,","}} kgs
                
            </td>
        </tr>
        <tr>
            <td class="side">Container Tare Weight:</td>
            <td>{{number_format($record->tare_weight),0,","}} lbs /
                {{number_format($record->tare_weight * 0.45359237),0,","}} kgs
            </td>
        </tr>

    </table>
    <table class="table1">
        <td class="side">Shipper's Reference:</td>
        <td>{{$record->batch->batch_year}}{{$record->batch->batchno}}
    </table>

</body>

</html>
