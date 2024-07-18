@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>NomorPesanan: #{{ $order->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    @include('admin.message ')
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <h1 class="h5 mb-3">Alamat Pengiriman</h1>
                                    <address>
                                        <strong>{{ $order->first_name . '' . $order->last_name }}</strong><br>
                                        {{ $order->address }}<br>
                                        {{ $order->city }}, {{ $order->subdistrict }}, {{ $order->zip }}
                                        {{ $order->provinceName }} <br>
                                        No.Hp: {{ $order->mobile }}<br>
                                        Email: {{ $order->email }}
                                    </address>
                                    <strong>Tanggal Pengiriman</strong><br>
                                    @if (!empty($order->shipped_date))
                                        {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                                    @else
                                        tak dapat diterapkan
                                    @endif

                                </div>




                                <div class="col-sm-4 invoice-col">

                                    <b>ID Pesanan:</b> {{ $order->id }}<br>
                                    <b>Total:</b> Rp {{ number_format($order->grand_total, 0, ',', '.') }}<br>
                                    <b>Status:</b>
                                    @if ($order->status == 'pending')
                                        <span class="text-danger">Menunggu</span>
                                    @elseif ($order->status == 'shipped')
                                        <span class="text-info">Dikirim</span>
                                    @elseif ($order->status == 'delivered')
                                        <span class="badge bg-success">Terkirim</span>
                                    @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th width="100">Harga</th>
                                        <th width="100">Qty</th>
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">
                                            Diskon:{{ !empty($order->coupon_code) ? '(' . $order->coupon_code . ')' : '' }}
                                        </th>
                                        <td>Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Pengiriman:</th>
                                        <td>Rp {{ number_format($order->shipping, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Status Pesanan</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                            Menunggu</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                            Dikirim</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                            Terkirim</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Dibatalkan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Tanggal pengiriman </label>
                                    <input placeholder="Tanggal Pengiriman" value="{{ $order->shipped_date }}"
                                        type="text" name="shipped_date" id ="shipped_date" class="from-control">
                                    <div class="mb-3">
                                        <button class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" name="sendInvoiceEmail" id="sendInvoiceEmail">
                                <h2 class="h4 mb-3">Kirim Inovice ke Email</h2>
                                <div class="mb-3">
                                    <select name="userType" id="userType" class="form-control">
                                        <option value="customer">Pembeli</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
            $('#shipped_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });

        
            $("#changeOrderStatusForm").submit(function(event) {
                event.preventDefault();
                if(confirm("Kamu yakin mau mengubah status?")){
                $.ajax({
                    url: '{{ route('orders.changeOrderStatus', $order->id) }}',
                    type: 'post',
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('orders.detail', $order->id) }}';

                    }
                });
         }
        });

        $("#sendInvoiceEmail").submit(function(event) {
            event.preventDefault();

            if(confirm("Kamu yakin mau mengirimnya ke email?")){
            $.ajax({
                url: '{{ route('orders.sendInvoiceEmail', $order->id) }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    window.location.href = '{{ route('orders.detail', $order->id) }}';

                }
            });
        }
        });
    </script>
@endsection
