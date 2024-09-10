@extends('layouts.default')

@section('title', 'Dashboard')

@section('content')
<section class="product-section">
  <div class="container">
    <h2 class="section-title">Products</h2>
    <div class="product-grid">
      @foreach ($products as $product)
        <div class="product-item">
          <a href="{{ route('single_products', ['id' => $product->id]) }}" class="product-image">
            <img src="{{ asset('products_image/'.$product->image) }}" alt="Product 1">
          </a>
          <div class="product-info">
            <h3 class="product-name">{{ $product->name }}</h3>
            <p class="product-price">$ {{ $product->price }}</p>
            <a href="{{ route('single_products', ['id' => $product->id]) }}" class="product-button">More Details</a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
  <nav aria-label="Pagination">
    <ul class="pagination justify-content-center">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">{{ __('Previous') }}</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('Previous') }}</a>
            </li>
        @endif

        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            @if ($i == $paginator->currentPage())
                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor

        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('Next') }}</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">{{ __('Next') }}</span>
            </li>
        @endif
    </ul>
  </nav>
</section>
<style>
    .product-section {
      padding: 50px 0;
      background-color: #f5f5f5;
    }
    .section-title {
      text-align: center;
      margin-bottom: 50px;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
    .product-item {
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }
    .product-item:hover {
      transform: translateY(-5px);
    }
    .product-image {
      display: block;
      width: 100%;
      height: 200px;
      overflow: hidden;
    }
    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .product-info {
      padding: 20px;
    }
    .product-name {
      margin: 0 0 10px;
      font-size: 18px;
      font-weight: bold;
    }
    .product-price {
      margin: 0 0 10px;
      font-size: 16px;
      color: #333;
    }
    .product-button {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #f04;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
    }
    .product-button:hover {
      background-color: #e03;
      text-decoration: none;
      color: white;
    }
</style>
@endsection