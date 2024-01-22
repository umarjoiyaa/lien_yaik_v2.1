<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Rejected</title>
</head>

<body>
    <img src="{{ asset('assets/images/lien_orig.png') }}" width="100" height="70">
    <h1>Hey {{ $user->name }}!</h1>

    <p>{{ $purchase->order_no }} Rejected!</p>

    <p>From: {{ $authUserName }}</p>

    <p>Proceed With: {{ url('/login') }}.</p>

    <p>Best regards,<br>Lien Yaik</p>
</body>

</html>
