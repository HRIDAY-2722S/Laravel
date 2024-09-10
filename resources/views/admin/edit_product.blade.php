<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-gray-100">
    @if (session('status'))
        <div class="fade-out" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background-color: #34C759; padding: 15px; border-radius: 10px;">{{ session('status') }}
        </div>

        <style>
            .fade-out {
                animation: fadeOut 2s forwards;
                animation-delay: 2s;
            }
            @keyframes fadeOut {
                to {
                    opacity: 0;
                    visibility: hidden;
                }
            }
        </style>

        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert-success').fadeOut(200, function() {
                        $(this).remove();
                    });
                }, 2000);
            });
        </script>
    @endif
    <div class="loader">
        <div class="loader-inner">
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
        </div>
    </div>
    @foreach ($products as $product)
    <div class="page-container">
        <main>        
            <section class="different-color-section">
                <div class="header">
                    <nav>
                        <ul>
                            <li><a href="{{ route('admindashboard') }}" class="active" style="">Dashboard</a></li>
                            <li><a href="{{ route('singleproducts', ['id' => $product->id]) }}" class="active" style="">Products</a></li>
                            <li><a href="{{ route('users') }}" class="active" style="">Users</a></li>
                            <li><a href="{{ route('add_products') }}" class="active" style="">Add Products</a></li>
                        </ul>
                    </nav>
                    <div class="user-dropdown">
                        <span>{{session('name') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5H7z"/></svg>
                        <ul class="dropdown-content">
                            <li>
                                <a href="{{ route('adminprofile') }}" class="dropdown-link">
                                    <i class="fas fa-user"></i>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-link">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" class="dropdown-link">
                                    <i class="fas fa-lock"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr>
            </section>
        </main>
    </div>
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-full w-full bg-white p-8 rounded shadow-md">
            <h2 class="text-2xl font-semibold mb-6">Update Product Information</h2>
            
            <form method="POST" action="{{ route('update_product', ['id' => $product->id]) }}" class="space-y-4" enctype="multipart/form-data">
                @csrf

                <div class="flex space-x-4">
                    <div class="flex-1">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                            <textarea id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 resize-textarea" @error('name') style=" border: 2px solid red !important;" @enderror autofocus>{{ $product->name }}</textarea>
                            @error('name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex-1">
                        <div>
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 resize-textarea" @error('description') style=" border: 2px solid red !important;" @enderror>{{ $product->description }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <div class="flex-1">
                        <div>
                            <label for="price" class="block font-medium text-sm text-gray-700">Price ($)</label>
                            <input type="text" id="price" name="price" value="{{ $product->price }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500" @error('price') style=" border: 2px solid red !important;" @enderror>
                            @error('price')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex-1">
                        <div>
                            <label for="image" class="block font-medium text-sm text-gray-700">Images</label>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 half-width"  @error('images') style=" border: 2px solid red !important;" @enderror multiple>
                            @error('images')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <?php $product_images = DB::table('products_images')->where('product_id', $product->id)->get(); ?>
                @if(!$product_images->isEmpty())
                    <div class="flex flex-wrap -mx-3 mb-6" style="margin-top: 20px;">
                        @foreach($product_images as $image)
                            <?php $images = explode(',', $image->images); ?>
                            @foreach($images as $img)
                            <div class="w-1/4 px-3 mb-6">
                                <img src="{{ asset('products_image/'.$img) }}" alt="Product Image" class="w-full h-48 object-cover rounded image">
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                @endif

                <div>
                    <button type="submit" class="update-button">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
    <script>
    $(document).ready(function() {
        $('.user-dropdown').click(function() {
            $(this).toggleClass('active');
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.user-dropdown').length) {
                $('.user-dropdown').removeClass('active');
            }
        });
        
        $('form').submit(function() {
            $('.loader').addClass('show');
        });

        $(document).ajaxComplete(function() {
            $('.loader').removeClass('show');
        });
    });

        
    </script>
</body>
</html>
