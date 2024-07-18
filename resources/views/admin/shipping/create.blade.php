@extends('admin.layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Managemen Pengiriman</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('shipping.create') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <form action="{{ route('shipping.store') }}" method="post" id="shippingForm" name="shippingForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <select name="province" id="province" class="form-control">
                                        <option value="">Pilih Provinsi</option>
                                        @if ($provinces->isNotEmpty())
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input type="text" name="amount" id="amount" class="form-control"
                                        placeholder="Harga">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Buat Pengiriman</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">

                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                                @if ($shippingCharges->isNotEmpty())
                                    @foreach ($shippingCharges as $shippingCharge)
                                        <tr>
                                            <td>{{ $shippingCharge->id }}</td>
                                            <td>
                                                {{ $shippingCharge->name }}
                                            </td>
                                            <td>Rp {{ number_format($shippingCharge->amount, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('shipping.edit', $shippingCharge->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                <a href="javascript:void(0);"
                                                    onclick="deleteRecord({{ $shippingCharge->id }});"
                                                    class="btn btn-danger"> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

{{-- @section('customJs')
    <script>
        $("#shippingForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);

            $("button[type=submit]").prop('disabled,true');
            $.ajax({
                url: '{{ route('shipping.store') }}',
                type: 'post', // Pastikan menggunakan metode POST
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled,false');

                    if (response["status"] == true) {
                        window.location.href = "{{ route('shipping.create') }}";

                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");

                        $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");

                    } else {
                        var errors = response['errors']

                        if (errors['province']) {
                            $("#province").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['province']);
                        } else {
                            $("#province").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }

                        if (errors['amount']) {
                            $("#amount").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['amount']);
                        } else {
                            $("#amount").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }

                    }

                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                }
            })
        });


        function deleteRecord(id) {
            var url = '{{ route('shipping.delete', 'ID') }}';
            var newUrl = url.replace("ID", id)
            if (confirm("Are you sure you want to delete")) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    success: function(response) {
                        if (response["status"]) {
                            window.location.href = "{{ route('shipping.create') }}";
                        }
                    }


                });
            }
        }
    </script>
@endsection --}}
@section('customJs')
<script>
    $("#shippingForm").submit(function(event) {
        event.preventDefault();
        var element = $(this);

        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route('shipping.store') }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);

                if (response["status"] == true) {
                    
                    window.location.href = "{{ route('shipping.create') }}";
                } else {
                    // Tampilkan pesan error
                    var errors = response['errors'];

                    if (errors['province']) {
                        $("#province").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['province']);
                    } else {
                        $("#province").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }

                    if (errors['amount']) {
                        $("#amount").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['amount']);
                    } else {
                        $("#amount").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
            }
        })
    });

    function deleteRecord(id) {
        var url = '{{ route('shipping.delete', 'ID') }}';
        var newUrl = url.replace("ID", id)
        if (confirm("Apakah kamu yakin mau menghapus ini?")) {
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response["status"]) {
                        window.location.href = "{{ route('shipping.create') }}";
                    }
                }
            });
        }
    }
</script>
@endsection