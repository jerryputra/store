@extends('layouts.app')

@section('title')
    Store Homepage
@endsection

@section('content')
   <div class="page-content page-home">
    <section class="store-carousel">
      <div class="container">
        <div class="row">
          <div class="col-lg-12" data-aos="zoom-in">
            <div
              id="storeCarousel"
              class="carousel slide"
              data-ride="carousel"
            >
              <ol class="carousel-indicators">
                <li
                  class="active"
                  data-target="#storeCarousel"
                  data-slide-to="0"
                ></li>
                <li data-target="#storeCarousel" data-slide-to="1"></li>
                <li data-target="#storeCarousel" data-slide-to="2"></li>
              </ol>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img
                    src="/images/KEINDAHAN.png"
                    alt=""
                    class="d-block w-100"
                  />
                </div>
                <div class="carousel-item">
                  <img
                    src="/images/KEINDAHAN.png"
                    alt=""
                    class="d-block w-100"
                  />
                </div>
                <div class="carousel-item">
                  <img
                    src="/images/KEINDAHAN.png"
                    alt=""
                    class="d-block w-100"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="store-trend-catagories">
      <div class="container">
        <div class="row">
          <div class="col-12" data-aos="fade-up">
            <h5>Catagories</h5>
          </div>
        </div>
        <div class="row">
          @php
            $incrementCategory = 0
          @endphp
          @forelse ($categories as $category)
            <div
            class="col-6 col-md-3 col-lg-2"
            data-aos="fade-up"
            data-aos-delay="{{ $incrementCategory*= 100 }}"
            >
              <a href="{{ route('categories-detail', $category->slug) }}" class="component-catagories d-block">
                <div class="catagories-image">
                  <img src="{{ Storage::url($category->photo) }}" alt="" class="w-100" />
                  <p class="catagories-text">{{ $category->name }}</p>
                </div>
              </a>
          </div>
          @empty
            <div class="col-12 text-center py-5 " data-aos="fade-up" data-aos-delay="100">
              No Categories Found
            </div>   
          @endforelse
        </div>
      </div>
    </section>
    <section class="store-new-product">
      <div class="container">
        <div class="row">
          <div class="col-12" data-aos="fade-up">
            <h5>Products</h5>
          </div>
        </div>
        <div class="row">
          @php
            $incrementProduct = 0
          @endphp
            @forelse ($products as $product)
            <div
              class="col-6 col-md-4 col-lg-3"
              data-aos="fade-up"
              data-aos-delay="{{ $incrementProduct*= 100 }}"
              >
              <a href="{{ route('detail', $product->slug) }}" class="component-products d-block">
                <div class="product-tumbnail">
                  <div
                    class="product-image"
                    style="
                      @if ($product->galleries)
                        background-image: url('{{ Storage::url($product->galleries->first()->photo) }}')
                      @else
                        backgorund-color: #eee
                      @endif
                    "
                  ></div>
                </div>
                <div class="product-text">{{ $product->name }}</div>
                <div class="product-price">Rp {{ $product->price }}</div>
              </a>
            </div>
            @empty
              <div class="col-12 text-center py-5 " data-aos="fade-up" data-aos-delay="100">
                No Products Found
              </div>   
            @endforelse
          </div>
      </div>
    </section>
  </div>
@endsection