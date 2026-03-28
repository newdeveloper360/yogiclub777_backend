<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', env('APP_NAME'))</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @php
        $app_name = env('APP_NAME');
    @endphp
    <meta name="description" content="@yield('description', $app_name . ' Is Most Trusted Online Satta Matka App Where You Can Play Matka Games And Win Jackpots In Satta Industry ' . $app_name . ' Is Most Trusted And Reputed Gaming Platform Where Players Play Their Favorite Game Without Any Fear Or Worries ' . $app_name . ' Is Brand Since 2001')">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('favicon.png') }}' />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Custom css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
    <!-- Responsive midea css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/responsive.css') }}">
    @yield('css')
</head>

<body>
    <!-- header start    -->
    <header class="sticky-top">

        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="#"><img style="height: 50px;" src="{{ asset('logo.png') }}"
                        alt="img" class="img-fluid"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-center flex-grow-1 ">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" aria-current="page"
                                    href="/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('charts*') ? 'active' : '' }}" aria-current="page"
                                    href="/charts">Charts</a>
                            </li>

                        </ul>
                        <div class="text-lg-end Dapp">
                            <a href="{{ route('download-apk') }}">Download App</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- header end   -->

    @yield('content')

    <footer>
        <div class="container text-center">
            <p class="mb-0">Copyright ©
                <script>
                    document.write(new Date().getFullYear());
                </script> {{ env('APP_NAME') }}. All Rights Reserved
            </p>
        </div>
    </footer>
    <!-- footer top end  -->

    @yield('script')
    <!--  jQuery js-->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <!-- bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <!-- slick slider js cdn link -->
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <!-- fontawesome icon -->
    <script src=" {{ asset('assets/website/fontawesome/js/all.js') }}"></script>
    <!-- coustom js -->
</body>

</html>
