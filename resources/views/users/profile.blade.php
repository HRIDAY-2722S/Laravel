<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="{{asset('css/profile.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .loader.show {
            display: block;
        }

        .loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
        }

        .loader-inner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .loader-circle {
            width: 20px;
            height: 20px;
            margin: 5px;
            border-radius: 50%;
            background-color: #000;
            display: inline-block;
            animation: bounce 1.2s infinite ease-in-out;
        }

        @keyframes bounce {
            0%, 100% {
                transform: scale(0);
            }
            50% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="loader">
        <div class="loader-inner">
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
        </div>
    </div>
    
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
                            <li><a href="{{ route('usersdashboard') }}" class="active">Dashboard</a></li>
                            <li><a href="{{ route('usersproducts') }}" class="active">Products</a></li>
                            <li><a href="{{ route('usersorders') }}" class="active">Order</a></li>
                        </ul>
                    </nav>
                    <div class="user-dropdown">
                        <span>{{session('name') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5H7z"/></svg>
                        <ul class="dropdown-content">
                            <li>
                                <a href="{{ route('userprofile') }}" class="dropdown-link">
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
    @if(session('role') != "guest")
    @foreach ($users as $user)
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-full w-full bg-white p-8 rounded shadow-md">
            <h2 class="text-2xl font-semibold" style="font-size: 18px;"><b>Profile Information</b></h2>
            <h2 class="text-2xl font-semibold mb-4" style="font-size: 14px;">Update your account's information and email address</h2>
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2">
                    <form method="POST" action="{{ route('update_user_profile') }}" class="space-y-4" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5 font-bold" @error('name') style=" border: 2px solid red !important;" @enderror autofocus>
                            @error('name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                            <input type="text" id="email" name="email" value="{{ $user->email }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5" @error('email') style=" border: 2px solid red !important;" @enderror>
                            @error('email')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block font-medium text-sm text-gray-700">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ $user->phone }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5" @error('phone') style=" border: 2px solid red !important;" @enderror>
                            @error('phone')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="block font-medium text-sm text-gray-700">Profile Picture (If you choose not to change the profile picture, you can leave the section empty.)</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5" @error('image') style=" border: 2px solid red !important;" @enderror>
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
                <div class="md:w-1/2 flex justify-center mt-4 md:mt-0">
                    <div class="aspect-w-1 aspect-h-1" style="width: 320px; height: 320px;">
                        <img src="{{ asset('profile_picture/'.$user->profile_picture) }}" alt="User Profile Picture" class="w-full h-full object-cover rounded-md">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="min-h-screen flex items-center justify-center" style="margin-top: -100px;">
        <div class="max-w-full w-full bg-white p-8 rounded shadow-md">
            <h2 class="text-2xl font-semibold" style="font-size: 18px;"><b>Update Password</b></h2>
            <h2 class="text-2xl font-semibold mb-4" style="font-size: 14px;">Ensure your account is using a long, random password to stay secure</h2>
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2">
                    <form method="POST" action="{{ route('update_user_password') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="old_password" class="block font-medium text-sm text-gray-700">Old Password</label>
                            <input type="password" id="old_password" name="old_password" value="{{ old('old_password') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5 font-bold" @error('old_password') style=" border: 2px solid red !important;" @enderror autofocus>
                            @error('old_password')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password" class="block font-medium text-sm text-gray-700">New Password</label>
                            <input type="password" id="new_password" name="new_password" value="{{ old('new_password') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5" @error('new_password') style=" border: 2px solid red !important;" @enderror>
                            @error('new_password')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="confirm_password" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                            <input type="password" name="confirm_password" value="{{ old('confirm_password') }}" id="confirm_password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 pl-5" @error('confirm_password') style=" border: 2px solid red !important;" @enderror>
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
                <div class="md:w-1/2 flex justify-center mt-4 md:mt-0">
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-full w-full bg-white p-8 rounded shadow-md text-center">
            <h2 class="text-2xl font-semibold" style="font-size: 18px;"><b>Profile updates are not available in guest mode.</b></h2>
        </div>
    </div>
    @endif
    <!-- <div class="min-h-screen flex items-center justify-center" style="margin-top: -24%;">
        <div class="max-w-full w-full bg-white p-8 rounded shadow-md">
            <h2 class="text-2xl font-semibold " style="font-size: 18px;"><b>Delete Account</b></h2>
            <h2 class="text-2xl font-semibold mb-4" style="font-size: 14px;">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</h2>
            <div>
                <button onclick="deleteProduct()" class="update-button" style="width: auto; background-color: #f44336 !important;">
                    DELETE ACCOUNT
                </button>
            </div>
            <form method="POST" action="{{ route('delete_user_account') }}" class="space-y-4" id="delete-account">
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

    function deleteProduct() {
        if (confirm("Are you sure you want to delete your account?")) {
            document.getElementById("delete-account").submit();
        }
    }
    </script>
</body>
</html>
