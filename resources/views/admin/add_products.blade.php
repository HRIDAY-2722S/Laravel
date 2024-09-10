@extends('layouts.admindefault')

@section('title', 'Add Products || Admin')

@section('content')
<div class="flex items-center justify-center">
		<div class="max-w-full w-full bg-white p-8 rounded shadow-md">
			<h2 class="text-2xl font-semibold mb-6">Product Details</h2>
			<form method="POST" action="{{ route('add_products_details') }}" class="space-y-4" enctype="multipart/form-data">
				@csrf
				
				<div class="flex space-x-4">
					<div class="flex-1">
						<div>
							<label for="name" class="block font-medium text-sm text-gray-700">Name</label>
							<textarea id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 resize-textarea half-width" @error('name') style="border: 2px solid red !important;" @enderror autofocus>{{ old('name') }}</textarea>
							@error('name')
								<p style="color: red;">{{ $message }}</p>
							@enderror
						</div>
					</div>
					<div class="flex-1">
						<div>
							<label for="description" class="block font-medium text-sm text-gray-700">Description</label>
							<textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 resize-textarea half-width" @error('description') style="border: 2px solid red !important;" @enderror>{{ old('description') }}</textarea>
							@error('description')
								<p style="color: red;">{{$message }}</p>
							@enderror
						</div>
					</div>
				</div>
				
				<div class="flex space-x-4">
					<div class="flex-1">
						<div>
							<label for="price" class="block font-medium text-sm text-gray-700">Price ($)</label>
							<input type="text" id="price" name="price" value="{{ old('price') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 half-width" @error('price') style="border: 2px solid red !important;" @enderror>
							@error('price')
								<p style="color: red;">{{ $message }}</p>
							@enderror
						</div>
					</div>
					<div class="flex-1">
						<div>
							<label for="image" class="block font-medium text-sm text-gray-700">Images</label>
							<input type="file" name="images[]" id="images" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 half-width" @error('images') style="border: 2px solid red !important;" @enderror multiple>
							@error('images')
								<p style="color: red;">{{ $message }}</p>
							@enderror
						</div>
					</div>
				</div>
				
				<div>
					<button type="submit" class="update-button">
						Submit
					</button>
				</div>
			</form>
		</div>
	</div>
    <style>
		* {
			box-sizing: border-box;
		}

        body{
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            font-family: inherit;
            color: #6B7280;
        }
		
		.min-h-screen {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		
		.max-w-full {
			width: 95%;
			margin-top: 15px;
			margin-left: 15px;
			background-color: #fff;
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}
		
		.update-button {
			width: 80px;
			background-color: #000;
			color: #fff;
			padding: 8px 12px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
            margin-top: 20px;
		}
		
		.update-button:hover {
			background-color: #333;
		}
		
		.flex {
			display: flex;
		}
		
		.space-x-4 {
            margin-top: 20px;
			margin-left: -20px;
			margin-right: -20px;
		}
		
		.space-x-4 > * {
			margin-left: 20px;
			margin-right: 20px;
		}
		
		.flex-1 {
			flex: 1;
		}
		
		.text-2xl {
			font-size: 1.5rem;
		}
		
		.font-semibold {
			font-weight: 600;
		}
		
		.mb-6 {
			margin-bottom: 1.5rem;
		}
		
		.text-gray-700 {
			color: #333;
		}
		
		.block {
			display: block;
		}
		
		.font-medium {
			font-weight: 700;
		}
		
		.text-sm {
			font-size: 0.875rem;
		}
		
		.resize-textarea {
			resize: vertical;
		}
		
		.border {
			border: 1px solid #ddd;
		}
		
		.border-gray-300 {
			border-color: #ddd;
		}
		
		.rounded-md {
			border-radius: 0.25rem;
		}
		
		.shadow-sm {
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}
		
		.focus\:outline-none:focus {
			outline: none;
		}
		
		.focus\:border-blue-500:focus {
			border-color: #3498db;
		}
		
		.focus\:ring-blue-500:focus {
			box-shadow: 0 0 0 2px #3498db;
		}
		
		.half-width {
			width: 100%;
		}
	</style>
@endsection