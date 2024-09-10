@extends('layouts/default')

@section('title', 'Search || Users')

@section('content')

    <section class="search-results">
        <!-- <h3>Search Results for "<span id="search-query">{{ request()->input('query') }}</span>"</h3> -->
        <ul id="search-results-list">
            @if(count($searchResults) > 0)
                @foreach($searchResults as $result)
                    <a class="data" href="{{ route('single_products', ['id' => $result->id]) }}">
                        <li class="search-result">
                            <img src="{{ asset('products_image/' . $result->image) }}" alt="{{ $result->name }}">
                            <div class="user-info">
                                <h2>{{ $result->name }}</h2>
                                <div class="product-info">{{ $result->description }}</div>
                                <div class="price-info" style="margin-top: 10px;">${{ $result->price }}</div>
                            </div>
                        </li>
                    </a>
                @endforeach
            @else
                <h3>No results found</h3>
            @endif
        </ul>
    </section>
    <style>
        .search-results {
            background-color: #f7f7f7;
            padding: 2em;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .data:hover{
            text-decoration: none;
        }

        .search-result {
            transition: background-color 0.3s, box-shadow 0.3s, transform 0.1s;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .search-result:hover {
            transform: scale(1.01);
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 10px;
            color: #333;
        }

        .search-results h1 {
            margin-top: 0;
        }

        #search-results-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #search-results-list li {
            margin-top: 50px;
            margin-bottom: 20px;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        #search-results-list li:last-child {
            border-bottom: none;
        }

        .search-result {
            display: flex;
            align-items: center;
        }

        .search-result img {
            width: 100px;
            height: 100px;
            /* border-radius: 50%; */
            margin-right: 20px;
        }

        .search-result .user-info {
            flex: 1;
        }

        .search-result .user-info h2 {
            margin-top: 0;
        }

        .search-result .product-info {
            font-size: 16px;
            color: #666;
        }

        .search-result .price-info {
            font-size: 16px;
            color: #666;
            font-weight: bold;
        }
    </style>
@endsection