  @extends('front.layouts.app')

  @section('content')
      <section class="section-5 pt-3 pb-3 mb-3 bg-white">

          <div class="container">
              <div class="light-font">
                  <ol class="breadcrumb primary-color mb-0">
                      <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Halaman Utama</a></li>
                      <li class="breadcrumb-item active">Belanja</li>
                  </ol>
              </div>
          </div>
      </section>

      <section class="section-6 pt-5">
          <div class="container">
              <div class="row">
                  <div class="col-md-3 sidebar">
                      <div class="sub-title">
                          <h2>Kategori</h3>
                      </div>

                      <div class="card">
                          <div class="card-body">
                              <div class="accordion accordion-flush" id="accordionExample">
                                  @if ($categories->isNotEmpty())
                                      @foreach ($categories as $key => $category)
                                          <div class="accordion-item">
                                              @if ($category->sub_category->isNotEmpty())
                                                  <h2 class="accordion-header" id="headingOne">
                                                      <button class="accordion-button collapsed" type="button"
                                                          data-bs-toggle="collapse"
                                                          data-bs-target="#collapseOne-{{ $key }}"
                                                          aria-expanded="false"
                                                          aria-controls="collapseOne-{{ $key }}">
                                                          {{ $category->name }}
                                                      </button>
                                                  </h2>
                                              @else
                                                  <a href="{{ route('front.shop', $category->slug) }}"
                                                      class="nav-item nav-link">{{ $category->name }}</a>
                                              @endif

                                              @if ($category->sub_category->isNotEmpty())
                                                  <div id="collapseOne-{{ $key }}"
                                                      class="accordion-collapse collapse" aria-labelledby="headingOne"
                                                      data-bs-parent="#accordionExample">
                                                      <div class="accordion-body">
                                                          <div class="navbar-nav">
                                                              @foreach ($category->sub_category as $subCategory)
                                                                  <a href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}"
                                                                      class="nav-item nav-link">{{ $subCategory->name }}</a>
                                                              @endforeach


                                                          </div>
                                                      </div>
                                                  </div>
                                              @endif
                                          </div>
                                      @endforeach
                                  @endif
                              </div>
                          </div>
                      </div>

                      <div class="sub-title mt-5">
                          <h2>Merek</h3>
                      </div>

                      <div class="card">
                          <div class="card-body">
                              @if ($brands->isNotEmpty())
                                  @foreach ($brands as $brand)
                                      <div class="form-check mb-2">
                                          <input {{ in_array($brand->id, $brandsArray) ? 'checked' : '' }}
                                              class="form-check-input brand-label" type="checkbox" name="brand[]"
                                              value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                                          <label class="form-check-label" for="brand-{{ $brand->id }}">
                                              {{ $brand->name }}
                                          </label>
                                      </div>
                                  @endforeach
                              @endif
                          </div>
                      </div>

                      <div class="sub-title mt-5">
                          <h2>Harga</h3>
                      </div>

                      <div class="card">
                          <div class="card-body">
                            <input type="text" class="js-range-slider" name="my_range" value=""/>
                          </div>
                      </div>
                  </div>
                            
                          
                        
                  <div class="col-md-9">
                      <div class="row pb-3">
                          <div class="col-12 pb-1">
                              <div class="d-flex align-items-center justify-content-end mb-4">
                                  <div class="ml-2">

                          <select name="sort" id="sort" class="form-control">
                            <option value="latest" {{ ($sort == 'latest') ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : '' }}>Harga Tinggi</option>
                            <option value="price_asc" {{ ($sort == 'price_asc') ? 'selected' : '' }}>Harga Rendah</option>


                          </select>
                               </div>
                              </div>
                          </div>

                          @if ($products->isNotEmpty())
                              @foreach ($products as $product)
                                  @php
                                      $productImage = $product->product_images->first();
                                  @endphp


                                  <div class="col-md-4">
                                      <div class="card product-card">
                                          <div class="product-image position-relative">


                                              <a href="{{ route("front.product",$product->slug) }}" class="product-img">

                                                  @if (!empty($productImage->image))
                                                      <img class="ard-img-top"
                                                          src="{{ asset('uploads/product/' . $productImage->image) }}" />
                                                  @else
                                                      No Image Found
                                                  @endif
                                                   <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>

                                                  <div class="product-action">
                                                      <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                                          <i class="fa fa-shopping-cart"></i> masukkan keranjang
                                                      </a>
                                                  </div>
                                          </div>
                                          <div class="card-body text-center mt-3">
                                              <a class="h6 link" href="product.php">{{ $product->title }}</a>
                                              <div class="price mt-2">
                                                  <span class="h5"><strong>Rp
                                                          {{ number_format($product->price, 0, ',', '.') }}</strong></span>
                                                  @if ($product->compare_price > 0)
                                                      <span class="h6 text-underline"><del>Rp
                                                              {{ number_format($product->compare_price, 0, ',', '.') }}</del></span>
                                                  @endif
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              @endforeach
                          @endif


                          <div class="col-md-12 pt-5">
                            {{ $products->links() }}
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
  @endsection

  @section('customJs')
      {{-- <script>
          rangeSlider = $(".js-range-slider").ionRangeSlider({
              type: "double",
              min: 0,
              max: 2000000,
              from: {{ $priceMin }},
              step: 100000,
              to: {{ $priceMax }},
              skin: "round",
              max_postfix: "+",
              prefix: "Rp",
              onFinish: function() {
                  apply_filters()
              }

          });



          var slider = $(".js-range-slider").data("ionRangeSlider");
          $(".brand-label").change(function() {
              apply_filters();
          });

          function apply_filters() {
              var brands = [];

              $(".brand-label").each(function() {
                  if ($(this).is(":checked") == true) {
                      brands.push($(this).val());

                  }
              });

              console.log(brands.toString());
              var url = '{{ url()->current() }}?';

              url += '&price_min='+slider.result.from+'&price_max='+slider.result.to;

              window.location.href = url + '&brand=' + brands.toString();
          }
      </script> --}}
<script>
     
    rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 0,
        max: 5000000,
        from: {{ $priceMin }},
        to: {{ $priceMax }},
        step: 100000,
        skin: "round",
        max_postfix: "+",
        prefix: "Rp",
        onFinish: function() {
            apply_filters();
        }
    });

    var slider = $(".js-range-slider").data("ionRangeSlider");

    $(".brand-label").change(function() {
        apply_filters();
    });

    $("#sort").change(function(){
        apply_filters();
    });

    function apply_filters() {
        var brands = [];

        $(".brand-label").each(function() {
            if ($(this).is(":checked") == true) {
                brands.push($(this).val());
            }
        });

        var url = '{{ url()->current() }}?';

        

        //brand Filter
        if (brands.length > 0){
            url += '&brand=' + brands.toString();
        }

        //price range filter
        url += '&price_min=' + slider.result.from + '&price_max=' + slider.result.to;

        //sorting filter
        url += '&sort=' + $("#sort").val();
        window.location.href = url;
    }

</script>
  @endsection
