<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PURCHASE ORDER PRINT</title>
</head>

<body style="background: rgb(235, 235, 243);">
    <div class="container" style=" padding: 15px;">

        <div class="card">
            <div class="card-body">
                <div class="text-center" style="line-height: 2px; text-align: center;">
                    <h1>LIEN YAIK HARDWARE (M) SDN BHD</h1>

                    <h6>LOT 11537 , PERSIARAN EHSAN 1 , TAMAN IMPIAN EHSAN , OFF JALAN BALAKONG ,</h6>

                    <h6>43300 SERI KEMBANGAN , SELANGOR DARUL EHSAN</h6>

                    <h6>TEL : 016-550 2503 / 019-316 9888 , FAX : 03-8962 2503 , EMAIL : lienyaik@hostmail.com</h6>
                </div>
                <br>
                <div class="row">
                    <div style="float: left;text-transform: uppercase; line-height: 29px; width: 50%;">
                        <span>Customer Name :</span>
                        <br>
                        <span>Part Name :</span>
                        <br>
                        <span>Material :</span>
                        <br>
                        <span>Purchase Order No :</span>
                        <br>
                        <span>Order Date :</span>
                        <br>
                        <span>Customer Request Date :</span>
                        <br>
                        <span>Ordered Units :</span>
                        <br>
                        <span>Cavities :</span>
                        <br>
                        <span>Unit Kg :</span>
                        <br>
                        <span>WEIGHT PER MOLD :</span>
                        <br>
                        <span>Purchase Order Issued By :</span>
                        <br>
                        <span>Purchase Order Approved By :</span>
                    </div>
                    <div style="float: right;text-transform: uppercase; line-height: 29px; width: 50%;">
                        <span>{{ $data->customer }}</span>
                        <br>
                        <span>{{ $data->product->name }}</span>
                        <br>
                        <span>{{ $data->item->name }}</span>
                        <br>
                        <span>{{ $data->order_no }}</span>
                        <br>
                        <span>{{ $data->order_date }}</span>
                        <br>
                        <span>{{ $data->req_date }}</span>
                        <br>
                        <span>{{ $data->order_unit }}</span>
                        <br>
                        <span>{{ $data->cavities }}</span>
                        <br>
                        <span>{{ $data->unit_kg }}</span>
                        <br>
                        <span>{{ $data->per_mold }}</span>
                        <br>
                        <span>{{ $data->user->name }}</span>
                        <br>
                        <span>{{ $approved }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
