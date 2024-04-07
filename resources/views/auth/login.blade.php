@extends('layouts.main')

@section('title', 'Login')

@section('content')
    {{-- <div class="container"> --}}
        <div class="row justify-content-center">
            <div class="col-sm-4 pt-5">
                <div class="card card-box-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title">Login</h5>
                        <p class="card-text">Use these awesome forms to login your account.</p>
                        <form action="{{ route('login') }}" method="post"
                            class="h-100 d-flex flex-column align-items-center justify-content-center needs-validation @">
                            @csrf
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show col-sm-10">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="col-sm-10 mb-2">
                                {{-- <label for="username" class="form-label">Username</label> --}}
                                <input type="text" class="form-control" id="username" autocomplete="off" name="username" placeholder="Username"
                                    value="{{ old('username') }}" required>
                                <div class="invalid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-sm-10">
                                {{-- <label for="password" class="form-label">Password</label> --}}
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <div class="invalid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary col-sm-10">Login</button>
                        </form>
                        <p>Have no account? <a href="{{ route('register') }}">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
@endsection

@section('scripts')
    @if (session('error'))
        <script>
            $(document).ready(function() {
                var myAlert = $(".alert");
                setTimeout(function() {
                    myAlert.fadeOut(2000);
                }, 2000);
            });
        </script>
    @endif
@endsection
