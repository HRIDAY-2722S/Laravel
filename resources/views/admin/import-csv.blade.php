@extends('layouts.admindefault')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <h2>Users</h2>
        </div>
        <div class="col-md-6 text-right">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Download Sample Files
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('download_sample_csv') }}">Download Sample CSV</a>
                    <a class="dropdown-item" href="{{ route('download_sample_excel') }}">Download Sample Excel</a>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive" style="padding: 10px;">
        <form method="post" action="{{ route('insert_user') }}" enctype="multipart/form-data">
            @csrf
            <label for="csvFile">Browse...</label><br>
            <input type="file" class="form-control @error('csvFile') is-invalid @enderror" id="csvFile" name="csvFile" accept=".csv, .xls, .xlsx" style="height: 45px;"/>
            @error('csvFile')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
            <button type="submit" class="btn btn-success" style="float: right; margin: 10px 0;" id="upload_csv">Upload Users</button>
        </form>
    </div>
    <h4>Create a csv and an Excel file like this</h4>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>John Doe</td>
                <td>johndoe@example.com</td>
                <td>123</td>
            </tr>
            <tr>
                <td>Jane Smith</td>
                <td>janesmith@example.com</td>
                <td>123</td>
            </tr>
            <tr>
                <td>Bob Johnson</td>
                <td>bobjohnson@example.com</td>
                <td>123</td>
            </tr>
            <tr>
                <td>Alice Brown</td>
                <td>alicebrown@example.com</td>
                <td>123</td>
            </tr>
            <tr>
                <td>Mike Davis</td>
                <td>mikedavis@example.com</td>
                <td>123</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="loader-container" id="loaderContainer">
    <div class="loader"></div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
  $('#upload_csv').on('click', function() {
    $("#loaderContainer").addClass("active").fadeIn(); // Add active class and fade in the loader
  });
});

</script>
<style>
    #loaderContainer {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background-color: rgba(255, 255, 255, 0.8);
        display: none; 
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    #loaderContainer.active {
        display: flex;
    }

    #loaderContainer.active ~ *:not(#loaderContainer) {
        filter: blur(5px);
        pointer-events: none;
    }

    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #66d9ef;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1.5s linear infinite;
    }

    .loader::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        /* background-color: #66d9ef;  */
        opacity: 0.5;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .container {
        max-width: 95%;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: inherit;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .btn {
        padding: 8px 12px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }
    .btn-success {
        background-color: #4CAF50;
        color: white;
    }
    .btn-success:hover {
        background-color: #45a049;
    }
    .btn-primary {
        background-color: #337ab7;
        color: white;
    }
    .btn-primary:hover {
        background-color: #23527c;
    }
</style>
@endsection