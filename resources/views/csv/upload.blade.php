@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Upload Data CSV</h3>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <h5><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan:</h5>
            <hr>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card p-4 shadow-sm" style="border-radius: 12px;">
        <form action="{{ route('csv.upload.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="csv_file" class="fw-bold">Pilih file CSV:</label>
                <input type="file" name="csv_file" id="csv_file"
                       class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-upload"></i> Upload CSV
            </button>
        </form>
    </div>

</div>
@endsection
