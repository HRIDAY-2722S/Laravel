<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-4xl font-medium text-center mb-12"><i><b>Register</b></i></h1>
        <form class="w-full max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg" method="post" action="{{ route('userregister') }}" enctype="multipart/form-data">
            @csrf
            @if (Session::has('success'))
                <div class="alert alert-danger">{{ Session::get('success') }}</div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" @error('name') style="border: 2px solid red !important;" @enderror value="{{ old('name') }}">
                    @error('name')
                    <span style="color: red;">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="text" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" @error('email') style="border: 2px solid red !important;" @enderror value="{{ old('email') }}">
                    @error('email')
                    <span style="color: red;">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
                    <div class="flex">
                        <input type="text" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" @error('phone') style="border: 2px solid red !important;" @enderror value="{{ old('phone') }}">
                        <button type="button" id="send-otp" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500">Send</button>
                    </div>
                    @error('phone')
                    <span style="color: red;">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="otp" class="block text-gray-700 font-medium mb-2">OTP</label>
                    <input type="text" id="otp" name="otp" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" @error('otp') style="border: 2px solid red !important;" @enderror value="{{ old('otp') }}">
                    @error('otp')
                    <span style="color: red;">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="profile_image" class="block text-gray-700 font-medium mb-2">Profile Image</label>
                    <div class="flex items-center justify-center w-full">
                        <input type="file" id="profile_image" name="profile_image" class="hidden" />
                        <label for="profile_image" class="flex items-center justify-center w-full px-3 py-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100" @error('profile_image') style="border: 2px solid red !important;" @enderror>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="ml-2 text-gray-600">Upload Image</span>
                            <span id="selected-file" class="ml-2 text-gray-600"></span>
                        </label>
                    </div>
                    @error('profile_image')
                        <span style="color: red;">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" @error('password') style="border: 2px solid red !important;" @enderror value="{{ old('password') }}">
                    @error('password')
                    <span style="color: red;">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="confirm-password" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                    <input type="password" id="confirm-password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" @error('password_confirmation') style="border: 2px solid red !important;" @enderror value="{{ old('password_confirmation') }}">
                    @error('password_confirmation')
                    <span style="color: red;">{{$message}}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('login') }}" class="text-gray-600 text-sm mr-4 mt-2">Already registered?</a>
                <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500">REGISTER</button>
            </div>
            
            <!-- Social media buttons -->
            <div class="mt-6 mb-4">
                <p class="text-gray-600 text-center mb-4">Or sign up with:</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register_google') }}" class="flex items-center border border-gray-300 rounded-lg shadow-md px-6 py-2 text-sm font-medium text-gray-800 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 bg-white dark:border-slate-700 dark:text-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700" style="text-decoration: none; color: rgb(255 255 255); background-color: #3944BC !important;">
                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-0.5 0 48 48" version="1.1">
                            <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Color-" transform="translate(-401.000000, -860.000000)">
                                    <g id="Google" transform="translate(401.000000, 860.000000)">
                                        <path d="M9.82727273,24 C9.82727273,22.4757333 10.0804318,21.0144 10.5322727,19.6437333 L2.62345455,13.6042667 C1.08206818,16.7338667 0.213636364,20.2602667 0.213636364,24 C0.213636364,27.7365333 1.081,31.2608 2.62025,34.3882667 L10.5247955,28.3370667 C10.0772273,26.9728 9.82727273,25.5168 9.82727273,24" id="Fill-1" fill="#FBBC05"> </path>
                                        <path d="M23.7136364,10.1333333 C27.025,10.1333333 30.0159091,11.3066667 32.3659091,13.2266667 L39.2022727,6.4 C35.0363636,2.77333333 29.6954545,0.533333333 23.7136364,0.533333333 C14.4268636,0.533333333 6.44540909,5.84426667 2.62345455,13.6042667 L10.5322727,19.6437333 C12.3545909,14.112 17.5491591,10.1333333 23.7136364,10.1333333" id="Fill-2" fill="#EB4335"> </path>
                                        <path d="M23.7136364,37.8666667 C17.5491591,37.8666667 12.3545909,33.888 10.5322727,28.3562667 L2.62345455,34.3946667 C6.44540909,42.1557333 14.4268636,47.4666667 23.7136364,47.4666667 C29.4455,47.4666667 34.9177955,45.4314667 39.0249545,41.6181333 L31.5177727,35.8144 C29.3995682,37.1488 26.7323182,37.8666667 23.7136364,37.8666667" id="Fill-3" fill="#34A853"> </path>
                                        <path d="M46.1454545,24 C46.1454545,22.6133333 45.9318182,21.12 45.6113636,19.7333333 L23.7136364,19.7333333 L23.7136364,28.8 L36.3181818,28.8 C35.6879545,31.8912 33.9724545,34.2677333 31.5177727,35.8144 L39.0249545,41.6181333 C43.3393409,37.6138667 46.1454545,31.6490667 46.1454545,24" id="Fill-4" fill="#4285F4"> </path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        <span>Continue with Google</span>
                    </a>

                    <a href="{{ route('register_with_linkedin') }}" class="flex items-center border border-gray-300 rounded-lg shadow-md px-6 py-2 text-sm font-medium text-gray-800 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 bg-white dark:border-slate-700 dark:text-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700" style="text-decoration: none; color: rgb(255 255 255); background-color: #3944BC !important;">
                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 -2 44 44" version="1.1">
                            <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Color-" transform="translate(-702.000000, -265.000000)" fill="#007EBB">
                                    <path
                                        d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z"
                                        id="LinkedIn">

                                    </path>
                                </g>
                            </g>
                        </svg>
                        <span>Continue with LinkedIn</span>
                    </a>

                    <a href="{{ route('register_facebook') }}" class="flex items-center border border-gray-300 rounded-lg shadow-md px-6 py-2 text-sm font-medium text-gray-800 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 bg-white dark:border-slate-700 dark:text-slate-200 dark:bg-gray-800 dark:hover:bg-gray-700" style="text-decoration: none; color: rgb(255 255 255); background-color: #3944BC !important;">
                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 48 48" version="1.1">
                            <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Color-" transform="translate(-200.000000, -160.000000)" fill="#4460A0">
                                    <path
                                        d="M225.638355,208 L202.649232,208 C201.185673,208 200,206.813592 200,205.350603 L200,162.649211 C200,161.18585 201.185859,160 202.649232,160 L245.350955,160 C246.813955,160 248,161.18585 248,162.649211 L248,205.350603 C248,206.813778 246.813769,208 245.350955,208 L233.119305,208 L233.119305,189.411755 L239.358521,189.411755 L240.292755,182.167586 L233.119305,182.167586 L233.119305,177.542641 C233.119305,175.445287 233.701712,174.01601 236.70929,174.01601 L240.545311,174.014333 L240.545311,167.535091 C239.881886,167.446808 237.604784,167.24957 234.955552,167.24957 C229.424834,167.24957 225.638355,170.625526 225.638355,176.825209 L225.638355,182.167586 L219.383122,182.167586 L219.383122,189.411755 L225.638355,189.411755 L225.638355,208 L225.638355,208 Z"
                                        id="Facebook">

                                    </path>
                                </g>
                            </g>
                        </svg>
                        <span>Continue with Facebook</span>
                    </a>
                </div>
            </div>
            <!-- End social media buttons -->


            
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#send-otp').on('click', function(event) {
                event.preventDefault();
                
                var phoneValue = $('#phone').val();
                
                if (phoneValue) {
                    $.ajax({
                        url: '{{ route('generate_otp') }}',
                        type: 'POST',
                        data: {
                            phone: phoneValue,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {

                            alert('OTP sent to ' + phoneValue);
                        },
                        error: function(xhr) {

                            alert('Failed to send OTP. Please try again.');
                        }
                    });
                } else {
                    alert('Please enter your phone number.');
                }
            });
        });
    </script>
    
</body>
</html>
