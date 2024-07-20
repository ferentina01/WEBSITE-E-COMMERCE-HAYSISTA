<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset sandi email</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">

    <p>Hello, {{ $formData['user']->name }}</p>
    <h1>Anda meminta untuk mengubah kata sandi </h1>
    <p>Silahkan Klik tautan untuk mengatur ulang kata sandi</p>

    <a href="{{ route('front.resetPassword', $formData['token']) }}">Klik disini </a>
    <p>Thanks</p>
</body>


</html>