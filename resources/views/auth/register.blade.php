@extends('layouts.main')

@section('title', 'Register')

@section('content')

{{-- <div class="container"> --}}
    <div class="row justify-content-center">
        <div class="col-sm-4 pt-5">
            <div class="card card-box-shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">Register</h5>
                    <p class="card-text">Use these awesome forms to register your account.</p>
                    <form action="{{ route('register') }}" method="post"
                        class="h-100 d-flex flex-column align-items-center justify-content-center needs-validation was-validate">
                        @csrf
                        <div class="col-sm-10 mb-2">
                            <input type="text" class="form-control" name="name" autocomplete="off" id="name" placeholder="Name" value="{{ old('name') }}"
                                required>
                            @error('name')
                                <small class="text-danger text-sm">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="col-sm-10 mb-2">
                            <input type="text" class="form-control" name="username" autocomplete="off" id="username" placeholder="Username" value="{{ old('username') }}"
                                required>
                            @error('username')
                                <small class="text-danger text-sm">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" placeholder="Password" id="password" required>
                            @error('password')
                                <small class="text-danger text-sm">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary col-sm-10">Register</button>
                    </form>
                    <p>Aleady have an account? <a href="{{ route('login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}
@endsection
