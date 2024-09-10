@extends('layouts.admindefault')

@section('title', 'Dashboard')

@section('content')

<div class="container">
    @foreach ($products as $product)
    <div class="left-sidebar">
        <div class="main-image">
            <img src="{{ asset('products_image/'.$product->image) }}" alt="Main Image" id="main-image">
        </div>
        <?php $product_images = DB::table('products_images')->where('product_id', $product->id)->get(); ?>
        @if(!$product_images->isEmpty())
            <div class="more-images">
                @foreach($product_images as $image)
                    <?php $images = explode(',', $image->images); ?>
                    @foreach($images as $img)
                    <div class="image-container" style=" margin-left: 10px;">
                        <img src="{{ asset('products_image/'.$img) }}" alt="Product Image" onmouseover="changeMainImage(this.src)">
                    </div>
                    @endforeach
                @endforeach
            </div>
        @endif
    </div>
    <div class="main-content1">
        <div class="product-info">
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->description }}</p>
        </div>
        <div class="pricing-and-offers">
            <div class="pricing">
                <h2>Pricing</h2>
                <p>${{ $product->price }}</p>
                <?php $dicsount = $product->price * 10/100; ?>
                <p class="discounted-price">${{ round($product->price + $dicsount) }}</p>
            </div>
            <div class="call-to-action">
                <a href="{{ route('edit_product', ['id' => $product->id]) }}">Edit</a>
                <a href="#" onclick="deleteProduct({{ $product->id }});" class="btn btn-danger">Delete</a>
                <form id="delete-product-from-{{ $product->id }}" action="{{ route ('delete_product', $product->id) }}" method="post">
                    @csrf
                    @method('delete')
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function changeMainImage(src) {
        document.getElementById("main-image").src = src;
    }

    function deleteProduct (id) {
        if (confirm("Are you sure you want to delete product?")) {
        document.getElementById("delete-product-from-"+id).submit();
        }
    }

</script>

<style>
.container {
    max-width: 100%;
    /* margin: 40px auto; */
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
}
.left-sidebar {
    overflow: hidden;
    width: 40%;
    margin-right: 20px;
    flex-shrink: 0;
    /* border: 2px solid red; */
}
.main-image {
    margin-bottom: 20px;
    margin: 5px;
}
.main-image img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
}
.more-images {
    display: flex;
    overflow-x: auto;
    padding: 10px;
}
.more-images img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
    margin: 10px;
    cursor: pointer;
}
.more-images img:hover {
    border: 2px solid #337ab7;
}
.swipe-button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: #337ab7;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 18px;
    cursor: pointer;
}
.swipe-button:hover {
    background-color: #23527c;
}
.swipe-left {
    left: 10px;
}
.swipe-right {
    right: 10px;
}
.main-content1 {
    width: 100%!important;
    padding: 20px;
}
.product-info {
    margin-bottom: 20px;
}
.product-info h1 {
    font-size: 24px;
    margin-bottom: 10px;
}
.product-info p {
    font-size: 18px;
    color: #666;
}
.pricing-and-offers {
    margin-top: 20px;
}
.pricing {
    margin-bottom: 20px;
}
.pricing h2 {
    font-size: 18px;
    margin-bottom: 10px;
}
.pricing p {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}
.discounted-price {
    font-size: 18px;
    color: #666;
    text-decoration: line-through;
}
.call-to-action {
    margin-top: 20px;
    text-align: center;
}
.call-to-action a {
    background-color: #337ab7;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 18px;
    cursor: pointer;
    border-radius: 10px;
    margin: 10px;
}
.call-to-action button:hover {
    background-color: #23527c;
}
.image-container {
    position: relative;
    display: inline-block;
    /* margin: 10px; */
}

/* .delete-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: red;
    color: #fff;
    border: none;
    padding: 5px 10px;
    font-size: 12px;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.3s;
    border-radius: 10px;
    max-height: 44px !important;
}

.image-container:hover .delete-btn {
    opacity: 1;
} */
</style>

@endsection