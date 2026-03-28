<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title', 'Admin Dashboard') </title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/bundles/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/bundles/jquery-selectric/selectric.css') }}">
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('favicon.png') }}' />
    {{-- DataTables.net --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    @vite(['resources/js/app.js'])
    <style>
        table.dataTable td {
            font-size: 15px;
        }
    </style>
    @stack('styles')
</head>

<main>
    @yield('content')
</main>
<script src="{{ asset('assets/backend/js/app.min.js') }}"></script>
<!-- Template JS File -->
<script src="{{ asset('assets/backend/js/scripts.js') }}"></script>
<!-- Custom JS File -->
<script src="{{ asset('assets/backend/js/custom.js') }}"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            language: {
                zeroRecords: "No data available"
            }
        })
    });
</script>
@stack('scripts')
</body>

</html>
