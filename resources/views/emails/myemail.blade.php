<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Purchase Order</title>
</head>

<body>
    <img src="{{ asset('assets/images/lien_orig.png') }}" width="100" height="100">
    <h1>Hey !</h1>

    <p>This is the body of your email. You can include HTML and Blade syntax here.</p>

    <p>User Name: {{ $user->name }}</p>

    <p>Thank you for using our application.</p>

    <p>Best regards,<br>Your Name</p>
</body>

</html>
