@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Akun Saya</a></li>
                    <li class="breadcrumb-item">Pengaturan</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('front.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Informasi Pribadi</h2>
                        </div>
                        <form action="" name="profilForm" id="profilForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Nama</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name"
                                            placeholder="Masukkan Nama Kamu" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email"
                                            placeholder="Masukkan Email Kamu" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">No. Handphone</label>
                                        <input value="{{ $user->phone }}" type="text" name="phone" id="phone"
                                            placeholder="Masukkan No.Hp Kamu" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Alamat</h2>
                        </div>
                        <form action="" name="addressForm" id="addressForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Nama Depan</label>
                                        <input value="{{ (!empty($address)) ? $address->first_name : '' }}" type="text" name="first_name" id="first_name"
                                            placeholder="Masukkan Nama Depan" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Nama Belakang</label>
                                        <input value="{{ (!empty($address)) ? $address->last_name : '' }}" type="text" name="last_name" id="last_name" placeholder="Masukkan Nama Belakang" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ (!empty($address)) ? $address->email : '' }}" type="text" name="email" id="email"
                                            placeholder="Masukkan Email " class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone">No. Handphone</label>
                                        <input value="{{ (!empty($address)) ? $address->mobile : '' }}" type="text" name="mobile" id="mobile"
                                            placeholder="Masukkan No.Hp " class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Provinsi</label>
                                        <select name="province_id" id="province_id" class="form-control">
                                            <option value="">Pilih Provinsi</option>
                                            @if ($provinces->isNotEmpty())
                                                @foreach ($provinces as $province)
                                                    <option {{ (!empty($address) && $address->province_id == $province->id) ? 'selected' : ''  }} value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Alamat</label>
                                        <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ (!empty($address)) ? $address->address : '' }}</textarea>
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="phone">Apartment</label>
                                        <input value="{{ (!empty($address)) ? $address->apartment : '' }}" type="text" name="apartment" id="apartment" placeholder="Apartement, rumah atau lainnya" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="phone">Kota</label>
                                        <input value="{{ (!empty($address)) ? $address->city : '' }}" type="text" name="city" id="city" placeholder="Masukkan kota" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="phone">Kecamatan</label>
                                        <input value="{{ (!empty($address)) ? $address->subdistrict : '' }}" type="text" name="subdistrict" id="subdistrict" placeholder="Masukkan Kecamatan" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="phone">Kode Pos</label>
                                        <input value="{{ (!empty($address)) ? $address->zip : '' }}" type="text" name="zip" id="zip" placeholder="Masukkan Kode Pos" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#profilForm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route('account.updateProfile') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {

                        $("#profilForm #name").removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');

                        $("#profilForm #email").removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');

                        $("#profilForm #phone").removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                        window.location.href = '{{ route('account.profile') }}';


                    } else {
                        let errors = response.errors;
                        if (errors.name) {
                            $("#profilForm #name").addClass('is-invalid').siblings('p').html(errors.name).addClass(
                                'invalid-feedback');
                        } else {
                            $("#profilForm #name").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.email) {
                            $("#profilForm #email").addClass('is-invalid').siblings('p').html(errors.email)
                                .addClass('invalid-feedback');
                        } else {
                            $("#profilForm #email").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.phone) {
                            $("#profilForm #phone").addClass('is-invalid').siblings('p').html(errors.phone)
                                .addClass('invalid-feedback');
                        } else {
                            $("#profilForm #phone").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }
                    }
                },
                error: function(xhr) {
                    // This block will be executed in case of an error
                    console.log(xhr.responseText);
                }
            });
        });


         $("#addressForm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route('account.updateAddress') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {

                        $("#addressForm #name").removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');

                        $("#addressForm #email").removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');

                        $("#addressForm #phone").removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                        window.location.href = '{{ route('account.profile') }}';


                    } else {
                        let errors = response.errors;
                        if (errors.first_name) {
                            $("#first_name").addClass('is-invalid').siblings('p').html(errors.first_name).addClass(
                                'invalid-feedback');
                        } else {
                            $("#first_name").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.last_name) {
                            $("#last_name").addClass('is-invalid').siblings('p').html(errors.last_name)
                                .addClass('invalid-feedback');
                        } else {
                            $("#last_name").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.email) {
                            $("#addressForm #email").addClass('is-invalid').siblings('p').html(errors.email)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #email").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                      
                        
                        if (errors.mobile) {
                            $("#addressForm #mobile").addClass('is-invalid').siblings('p').html(errors.mobile)
                                .addClass('invalid-feedback');
                        } else {
                            $("#addressForm #mobile").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.province_id) {
                             $("#province_id").addClass('is-invalid').siblings('p').html(errors.province_id).addClass('invalid-feedback');
                        } else {
                            $("#province_id").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.address) {
                            $("#address").addClass('is-invalid').siblings('p').html(errors.address)
                                .addClass('invalid-feedback');
                        } else {
                            $("#address").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.apartment) {
                            $("#apartment").addClass('is-invalid').siblings('p').html(errors.apartment)
                                .addClass('invalid-feedback');
                        } else {
                            $("#apartment").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.city) {
                            $("#city").addClass('is-invalid').siblings('p').html(errors.city)
                                .addClass('invalid-feedback');
                        } else {
                            $("#city").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }

                        if (errors.subdistrict) {
                            $("#subdistrict").addClass('is-invalid').siblings('p').html(errors.subdistrict)
                                .addClass('invalid-feedback');
                        } else {
                            $("#subdistrict").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }
                        if (errors.zip) {
                            $("#zip").addClass('is-invalid').siblings('p').html(errors.zip)
                                .addClass('invalid-feedback');
                        } else {
                            $("#zip").removeClass('is-invalid').siblings('p').html('').removeClass(
                                'invalid-feedback');
                        }
                    


                    }
                },
                error: function(xhr) {
                    // This block will be executed in case of an error
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection
