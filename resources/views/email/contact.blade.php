<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kontak Email</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">

    
    <h1>Anda  menerima email kontak</h1>
    <p>Nama: {{ $mailData['name'] }}</p> 
    <p>Email: {{ $mailData['email'] }}</p>
     <p>Subject: {{ $mailData['subject'] }}</p>
    <p>Pesan:</p>
    <p>{{ $mailData['message'] }}</p>

</body>
</html>