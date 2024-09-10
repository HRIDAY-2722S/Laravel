<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="{{asset('css/profile.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="loader">
        <div class="loader-inner">
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
        </div>
    </div>
    @foreach ($users as $user)
    <div class="page-container">
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
        <main>        
            <section class="different-color-section">
                <div class="header">
                    <nav>
                        <ul>
                            <li><a href="{{ route('admindashboard') }}" class="active" style="">Dashboard</a></li>
                            <li><a href="{{ route('products') }}" class="active" style="">Products</a></li>
                            <li><a href="{{ route('users') }}" class="active" style="">Users</a></li>
                            <li><a href="{{ route('add_products') }}" class="active" style="">Add Products</a></li>
                            <li><a href="{{ route('users_orders') }}" class="active" style="">Orders</a></li>
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
    <div class="min-h-screen flex items-center justify-center" style="margin-top: -5%;">
        <div class="max-w-full w-full bg-white p-8 rounded shadow-md">
            <h2 class="text-2xl font-semibold " style="font-size: 18px;"><b>Profile Information</b></h2>
            <h2 class="text-2xl font-semibold mb-4" style="font-size: 14px;">Update your accont's information and email address</h2>
            <div class="flex">
                <div class="w-1/2">
                    <form method="POST" action="{{ route('update_profile') }}" class="space-y-4" enctype="multipart/form-data">
                        @csrf

                        <div class="w-2/2">
                            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5 text-bold" @error('name') style=" border: 2px solid red !important;" @enderror autofocus>
                            @error('name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="w-2/2">
                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                            <input type="text" id="email" name="email" value="{{ $user->email }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5" @error('description') style=" border: 2px solid red !important;" @enderror>
                            @error('email')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="w-2/2">
                            <label for="image" class="block font-medium text-sm text-gray-700">Profile Picture (If you choose not to change the profile picture, you can leave the section empty.)</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5"  @error('image') style=" border: 2px solid red !important;" @enderror>
                            @error('image')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="update-button">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
                <div class="w-1/2 flex justify-center">
                    <div class="aspect-w-1 aspect-h-1" style="width: 320px; height: 320px;">
                        <img src="{{ asset('profile_picture/'.$user->profile_picture) }}" alt="User Profile Picture" class="w-full h-full object-cover rounded-md">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="min-h-screen flex items-center justify-center" style="margin-top: -10%;">
        <div class="max-w-full w-full bg-white p-8 rounded shadow-md">
            <h2 class="text-2xl font-semibold " style="font-size: 18px;"><b>Update Password</b></h2>
            <h2 class="text-2xl font-semibold mb-4" style="font-size: 14px;">Ensure your account is using a long, random password to stay secure</h2>
            <form method="POST" action="{{ route('update_password') }}" class="space-y-4">
                @csrf

                <div class="w-1/2">
                    <label for="name" class="block font-medium text-sm text-gray-700">Old password</label>
                    <input type="password" id="old_password" name="old_password" value="{{ old('old_password') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5 text-bold" @error('old_password') style=" border: 2px solid red !important;" @enderror autofocus>
                    @error('old_password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-1/2">
                    <label for="new_password" class="block font-medium text-sm text-gray-700">New Password</label>
                    <input type="password" id="new_password" name="new_password" value="{{ old('new_password') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5" @error('new_password') style=" border: 2px solid red !important;" @enderror>
                    @error('new_password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-1/2">
                    <label for="confirm_password" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                    <input type="password" name="confirm_password" value="{{ old('confirm_password') }}" id="confirm_password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5"  @error('confirm_password') style=" border: 2px solid red !important;" @enderror>
                    @error('confirm_password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="update-button">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- <div class="min-h-screen flex items-center justify-center" style="margin-top: -26%;">
        <div class="max-w-full w-full bg-white p-8 rounded shadow-md">
            <h2 class="text-2xl font-semibold " style="font-size: 18px;"><b>Delete Account</b></h2>
            <h2 class="text-2xl font-semibold mb-4" style="font-size: 14px;">Once your account is deleted, all of it's resources and data will be permanently deleted. Before deleting your account please download any data or information that you wish to retain.</h2>
            <div>
                <button onclick="deleteProduct()" class="update-button" style="width: auto; background-color: #f44336 !important;">
                    DELETE ACCOUNT
                </button>
            </div>
            <form method="POST" action="{{ route('delete_account') }}" class="space-y-4" id="delete-account">
                @csrf
                @method('delete')
            </form>
        </div>
    </div> -->
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

    function deleteProduct () {
        if (confirm("Are you sure you want to delete your account?")) {
        document.getElementById("delete-account").submit();
        }
    }
    </script>
</body>
</html>
