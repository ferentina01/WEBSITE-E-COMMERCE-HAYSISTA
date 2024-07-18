<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Pesanan</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">

    @if($mailData['userType'] == 'customer')

    <h1>Trimakasih Banyak sudah melakukan pembelian produk</h1>
    <h2>Pesanan Kamu: #{{ $mailData['order']->id }}</h2>
    @else
    <h1>Anda Menerima Pesanan</h1>
    <h2>Pesanan : #{{ $mailData['order']->id }}</h2>

    @endif

    <h2>Alamat Pengiriman</h2>
    <address>
        <strong>{{ $mailData['order']->first_name . '' . $mailData['order']->last_name }}</strong><br>
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }}, {{ $mailData['order']->subdistrict }}, {{ $mailData['order']->zip }}
        {{ getProvinceInfo($mailData['order']->province_id)->name }} <br>
        No.Hp: {{ $mailData['order']->mobile }}<br>
        Email: {{ $mailData['order']->email }}
    </address>
    <h2>Produk</h2>

    <table cellpadding="3" cellspacing="3" border="0" width="700">
        <thead>
            <tr style="background: #ccc;">
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr>
                <th colspan="3" align="right">Subtotal:</th>
                <td>Rp {{ number_format($mailData['order']->subtotal, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <th colspan="3" align="right">
                    Diskon:{{ !empty($mailData['order']->coupon_code) ? '(' . $mailData['order']->coupon_code . ')' : '' }}
                </th>
                <td>Rp {{ number_format($mailData['order']->discount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Pengiriman:</th>
                <td>Rp {{ number_format($mailData['order']->shipping, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Total:</th>
                <td>Rp {{ number_format($mailData['order']->subtotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
