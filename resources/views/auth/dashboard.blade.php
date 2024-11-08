@extends('auth.layouts')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                <!-- Menampilkan pesan sukses jika ada session message -->
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Menampilkan pesan error jika ada session error -->
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Cek apakah user admin atau bukan -->
                @if (auth()->user()->level === 'admin')
                    <div class="alert alert-success">
                        Anda adalah admin
                    </div>
                @else
                    <div class="alert alert-danger">
                        Anda bukan admin
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
