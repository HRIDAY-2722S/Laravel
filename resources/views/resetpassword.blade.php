<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-medium text-center mb-12"><i><b>Reset Password</b></i></h1>
        <form class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg" method="post" action="{{ route('reset_password', ['token' => $user->remember_token]) }}">
            @csrf
            @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">New Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" @error('password') style="border: 2px solid red !important;" @enderror value="{{ old('password') }}">
                @error('password')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                <input type="password" id="password" name="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" @error('confirm_password') style="border: 2px solid red !important;" @enderror value="{{ old('confirm_password') }}">
                @error('confirm_password')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500">Reset</button>
            </div>
        </form>
    </div>
</body>
</html>