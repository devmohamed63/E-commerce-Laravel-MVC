<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Locally</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('components.cart-panel')
    @include('components.navbar')

    <main class="container">
        @yield('content')
    </main>

    @include('components.footer')
    @include('components.product-detail-modal')
    @include('components.customer-form-overlay')
</body>
</html>
