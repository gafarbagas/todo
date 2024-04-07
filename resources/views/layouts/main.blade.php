<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
    @yield('styles')
</head>

<body>

    @include('layouts.navbar')

    <!-- Main Content -->
    <div class="main-content m-2">
        <div class="container">
            @yield('content')
        </div>
    </div>

    @include('layouts.script')
    @yield('scripts')

</body>

</html>
