@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Halaman Utama</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="#">Belanja</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form id= "orderForm" name="orderForm" action="" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Alamat Pengiriman</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    {{-- <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Nama Depan"
                                                value=" {{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Nama Belakang"
                                                value=" {{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email"
                                                value=" {{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="province" id="province" class="form-control">
                                                <option value="">Pilih Provinsi</option>
                                                @if ($province->isNotEmpty())
                                                    @foreach ($province as $provinsi)
                                                        <option
                                                            {{ !empty($customerAddress) && $customerAddress->province_id == $provinsi->id ? 'selected' : '' }}
                                                            value="{{ $provinsi->id }}">{{ $provinsi->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Alamat" class="form-control" {{ !empty($customerAddress) ? $customerAddress->address : '' }}></textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)"
                                                value=" {{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="Kota"
                                                value=" {{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="subdistrict" id="subdistrict" class="form-control"
                                                placeholder="Kecamatan"
                                                value=" {{ !empty($customerAddress) ? $customerAddress->subdistrict : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Kode pos"
                                                value=" {{ !empty($customerAddress) ? $customerAddress->zip : '' }}">


                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="No Handphone."
                                                value=" {{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Nama Depan"
                                                value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Nama Belakang"
                                                value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email"
                                                value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="province" id="province" class="form-control">
                                                <option value="">Pilih Provinsi</option>
                                                @if ($province->isNotEmpty())
                                                    @foreach ($province as $provinsi)
                                                        <option
                                                            {{ !empty($customerAddress) && $customerAddress->province_id == $provinsi->id ? 'selected' : '' }}
                                                            value="{{ $provinsi->id }}">{{ $provinsi->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Alamat" class="form-control">{{ !empty($customerAddress) ? $customerAddress->address : '' }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)"
                                                value="{{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="Kota"
                                                value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="subdistrict" id="subdistrict" class="form-control"
                                                placeholder="Kecamatan"
                                                value="{{ !empty($customerAddress) ? $customerAddress->subdistrict : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Kode pos"
                                                value="{{ !empty($customerAddress) ? $customerAddress->zip : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="No Handphone."
                                                value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Catatan Pesanan(optional)"
                                                class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Ringkasan Pesanan</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h9">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h8">Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach


                                {{-- <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    
                                    <div class="h6"><strong>Rp {{ number_format(str_replace(',', '', Cart::subtotal()), 0, ',', '.') }}</strong></div>

                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Pengiriman</strong></div>
                                    <div class="h6"><strong>Rp {{ number_format($totalShippingCharge, 0, ',', '.') }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></div>
                                </div>
                            </div>
                        </div> --}}
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>Rp {{ number_format($subtotalNumeric, 0, ',', '.') }}</strong></div>
                                </div>

                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Pengiriman</strong></div>
                                    <div class="h6"><strong id="shippingAmount">Rp {{ number_format($totalShippingCharge, 0, ',', '.') }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong id="grandTotal">Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                                    </div>
                                </div>


                                <div class="card payment-form ">
                                    <h3 class="card-title h5 mb-3">Detail Pembayaran</h3>
                                    <div class="card-body p-0">
                                        <div class="mb-3">

                                            <div class="">
                                                <input checked type="radio" name="payment_method" value="cod"
                                                    id="payment_method_one"> <label for="payment_method_one"
                                                    class="form-check-label">COD</label>
                                            </div>
                                            <div class="">
                                                <input type="radio" name="payment_method" value="cod"
                                                    id="payment_method_two">
                                                <label for="payment_method_two" class="form-check-label">Kartu</label>
                                            </div>

                                            <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                                                <div class="mb-3">
                                                    <label for="card_number" class="mb-2">No. Kartu</label>
                                                    <input type="text" name="card_number" id="card_number"
                                                        placeholder="Valid Card Number" class="form-control">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="expiry_date" class="mb-2">Tanggal Kadaluarsa
                                                        </label>
                                                        <input type="text" name="expiry_date" id="expiry_date"
                                                            placeholder="MM/YYYY" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                                        <input type="text" name="expiry_date" id="expiry_date"
                                                            placeholder="123" class="form-control">
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="pt-4">
                                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Belanja Sekarang</a> --}}
                                                <button type="submit" class="btn-dark btn btn-block w-100">Belanja
                                                    Sekarang</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- CREDIT CARD FORM ENDS HERE -->

                            </div>
                        </div>
            </form>
        </div>
    </section>
@endsection


@section('customJs')
    <script>
        $("#payment_method_one").click(function() {
            if ($(this).is(":checked") == true) {

                $("#card-payment-form").addClass('d-none');
            }
        });


        $("#payment_method_two").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").removeClass('d-none');
            }
        });



        $("#orderForm").submit(function(event) {
            event.preventDefault();

            $('button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: '{{ route('front.processCheckout') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    var errors = response.errors;
                    $('button[type="submit"]').prop('disabled', false);
                    //front.thanks
                    if (response.status == true) {
                        window.location.href = "{{ route('front.thanks', ['orderId' => ':orderId']) }}"
                            .replace(':orderId', response.orderId);
                    } else {
                        if (errors.first_name) {
                            $("#first_name").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.first_name);
                        } else {

                            $("#first_name").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.last_name) {
                            $("#last_name").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.last_name);
                        } else {

                            $("#last_name").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.email);
                        } else {

                            $("#email").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.province) {
                            $("#province").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.province);
                        } else {

                            $("#province").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.address) {
                            $("#address").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.address);
                        } else {

                            $("#address").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.city) {
                            $("#city").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.city);
                        } else {

                            $("#city").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.subdistrict) {
                            $("#subdistrict").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.subdistrict);
                        } else {

                            $("#subdistrict").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.zip) {
                            $("#zip").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.zip);
                        } else {

                            $("#zip").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.mobile) {
                            $("#mobile").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.mobile);
                        } else {

                            $("#mobile").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                    }

                }

            });
        });


        $("#province").change(function() {
        $.ajax({
        url: '{{ route('front.getOrderSummery') }}',
            type: 'get',
            data: {
                province_id: $(this).val()
            }, 
            dataType: 'json',
            success: function(response) {
                if(response.status == true){
                   $("#shippingAmount").html(response.shippingCharge);
                    $("#grandTotal").html(response.grandTotal);

                }
            }
        });
    });
    </script>
@endsection
