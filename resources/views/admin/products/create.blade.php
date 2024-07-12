@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Buat Produk Baru</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="{{ route('products.store') }}" method="post" name="productForm" id="productForm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Nama Produk</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Title">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Slug</label>
                                            <input type="text" readonly name="slug" id="slug"
                                                class="form-control" placeholder="slug">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Deskripsi Produk</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Tarik file disini atau klik untuk upload <br><br>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="product-gallery">
                            @if ($productImages->isNotEmpty())
                                @foreach ($productImages as $image)
                                    <div class="col-md-3" id="image-row-{{ $image->id }}">
                                        <div class="card">
                                            <input type="hidden" name="image_array[]" value="{{ $image->id }}">
                                            <img src="{{ asset('uploads/product/' . $image->image) }}" class="card-img-top"
                                                alt="">
                                            <div class="card-body">
                                                <a href="javascript:void(0)" onclick="deleteImage({{ $image->id }})"
                                                    class="btn btn-danger">Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Harga Produk</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Harga</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Price"><p class="error"></p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Harga Compare</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control" placeholder="Compare Price"><p class="error"></p>
                                            <p class="text-muted mt-3">
                                                Untuk menampilkan harga diskon, pindahkan harga asli produk ke harga "Harga
                                                Compare". Masukkan harga yang lebih rendah ke dalam bidang "Harga"
                                            </p>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Persediaan</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="sku"><p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode"><p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty"
                                                    name="track_qty" value="Yes" checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Qty">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Status Produk</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Aktif</option>
                                        <option value="0">Blok</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Kategori produk </h2>
                                <div class="mb-3">
                                    <label for="category">Kategori</label>
                                    <select name="category_id" id="category" class="form-control">
                                        <option value="">Pilih Kategori</option>

                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                    
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub Kategori</label>
                                    <select name="sub_category_id" id="sub_category" class="form-control">
                                        <option value="">Pilih Sub Kategori</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3"> Merek Produk</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Pilih Merek</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif


                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Produk Unggulan</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No">Tidak</option>
                                        <option value="Yes">Iya</option>
                                    </select><p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Buat Produk</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Batalkan</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#title").change(function() {
            element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }

                }

            });
        });

        $("#productForm").submit(function(event) {
            event.preventDefault();
            var formArray = $(this).serializeArray();
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: "{{ route('products.store') }}",
                type: 'post',
                data: formArray,
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);

                    if (response['status'] == true) {
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
                        window.location.href = "{{ route('products.index') }}";

                    } else {
                        var errors = response['errors'];

                        // if (errors['title']) {
                        //     $("#title").addClass('is-invalid')
                        //         .siblings('p')
                        //         .addClass('invalid-feedback').html(errors['title']);
                        // } else {
                        //     $("#title").removeClass('is-invalid')
                        //         .siblings('p')
                        //         .removeClass('invalid-feedback').html("");
                        // }

                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value);

                        });

                    }

                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        });

        $("#category").change(function() {
            var category_id = $(this).val();
            $.ajax({
                url: '{{ route('product-subcategories.index') }}',
                type: 'get',
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function(response) {
                    //console.log(response);

                    $("#sub_category").find("option").not(":first").remove();
                    $.each(response["subCategories"], function(key, item) {
                        $("#sub_category").append(
                            `<option value='${item.id}'>${item.name}</option>`);
                    });

                },
                error: function() {
                    console.log("Something Went Wrong");
                }
            });
        });


        // Dropzone.autoDiscover = false;
        // const dropzone = $("#image").dropzone({

        //     url: "{{ route('temp-images.create') }}",
        //     maxFiles: 10,
        //     paramName: 'image',
        //     addRemoveLinks: true,
        //     acceptedFiles: "image/jpeg,image/png,image/gif",
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     success: function(file, response) {
        //         $("#image_id").val(response.image_id);
        //         //console.log(response)




        //         var html = `<div class="col-md-3"><div class="card">
    //         <input type="hidden" name="image_array[]" value="${response.image_id}">
    //         <img src="${response.ImagePath}" class="card-img-top" alt="">
    //         <div class="card-body">
    //             <a href="#" class="btn btn-danger">Delete</a>
    //         </div>
    //         </div>
    //         </div>`;

        //         //     $("#product-gallery").append(html);
        //         // var html = ` 
    //         //             <div class="card">
    //         //                 <input type="hidden" name="image_array[]" value="${response.image_id}">
    //         //                 <img src="${response.ImagePath}" class="card-img-top" alt="">
    //         //                 <div class="card-body">
    //         //                     <a href="#" class="btn btn-danger">Hapus</a>
    //         //                 </div>
    //         //             </div>`;

        //          $("#product-gallery").append(html);

        //     },


        // });

        // $("#productForm").submit(function(event) {
        //     event.preventDefault();
        //     var formArray = $(this).serializeArray();
        //     $("button[type='submit']").prop('disabled', true);

        //     $.ajax({
        //         url: '{{ route("products.store") }}',
        //         type: 'post',
        //         data: formArray,
        //         dataType: 'json',
        //         success: function(response) {
        //             $("button[type='submit']").prop('disabled', false);

        //             if (response['status'] == true) {
        //                 $(".error").removeClass('invalid-feedback').html('');
        //                 $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
        //                 window.location.href = "{{ route('products.index') }}";
        //             } else {
        //                 var errors = response['errors'];

        //                 $(".error").removeClass('invalid-feedback').html('');
        //                 $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

        //                 $.each(errors, function(key, value) {
        //                     $(`#${key}`).addClass('is-invalid')
        //                         .siblings('p')
        //                         .addClass('invalid-feedback')
        //                         .html(value[0]);
        //                 });
        //             }
        //         },
        //         error: function() {
        //             console.log("Something Went Wrong");
        //         }
        //     });
        // });

      

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            url: "{{ route('temp-images.create') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                $("#image_id").val(response.image_id);

                var html = `<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
            <input type="hidden" name="image_array[]" value="${response.image_id}">
            <img src="${response.ImagePath}" class="card-img-top" alt="">
            <div class="card-body">
                <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
            </div>
        </div></div>`;


                $("#product-gallery").append(html);
            },
            complete: function(file){
                this.removeFile(file);
            }

        });

        function deleteImage(id){
            $("#image-row-" + id).remove();

            if (confirm('Apakah kamu yakin untuk menghapus foto ini?')) {
                $.ajax({
                    url: '{{ route('product-images.destroy') }}',
                    type: 'delete', // Menggunakan DELETE method untuk penghapusan
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == true) {
                            alert(response.message);
                        } 
                    }
                });
            }
        }
    </script>
@endsection
