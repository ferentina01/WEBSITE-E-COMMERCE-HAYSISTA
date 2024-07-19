@extends('admin.layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ubah Sandi</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message ')
            <form action="" method="post" id="changePasswordForm" name="changePasswordForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="old_password">Sandi Lama</label>
                                    <input type="text" name="old_password" id="old_password" class="form-control"
                                        placeholder="Masukkan kata sandi lama anda">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="new_password">Sandi Baru</label>
                                    <input type="text" name="new_password" id="new_password" class="form-control"
                                        placeholder="Masukkan kata sandi Baru anda">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="confirm_password">Konfirmasi Sandi Baru</label>
                                <div class="mb-3">
                                    <input type="text"  name="confirm_password" id="confirm_password" class="form-control"
                                        placeholder="masukkan konfirmasi sandi baru">
                                    <p></p>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update Sandi Baru</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#changePasswordForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled,true');
            $.ajax({
                url: '{{ route('admin.processChangePassword') }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled,false');

                    if (response["status"] == true) {
                        window.location.href = "{{ route('admin.showChangePasswordForm') }}";

                    } else {
                        var errors = response['errors']
                        if (errors['old_password']) {
                            $("#old_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['old_password']);
                        } else {
                            $("#old_password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }
                        if (errors['new_password']) {
                            $("#new_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['new_password']);
                        } else {
                            $("#new_password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }
                        if (errors['confirm_password']) {
                            $("#confirm_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['confirm_password']);
                        } else {
                            $("#confirm_password").removeClass('is-invalid')
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

    </script>
@endsection
