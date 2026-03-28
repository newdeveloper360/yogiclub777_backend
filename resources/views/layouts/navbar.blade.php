<div>
    <div class="navbar-bg"></div>
    <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
            <ul class="navbar-nav mr-3">
                <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
                    collapse-btn">
                        <i style="color: black;" data-feather="align-justify"></i></a></li>
                <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                        <i style="color: black;" data-feather="maximize"></i>
                    </a>
                </li>
            </ul>
        </div>
        <ul class="navbar-nav navbar-right">
            <li class="dropdown"><a href="#" data-toggle="dropdown"
                    class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                        src="{{ asset('logo.png') }}" class="user-img-radious-style"> <span
                        class="d-sm-none d-lg-inline-block"></span></a>

                <div class="dropdown-menu dropdown-menu-right pullDown">
                    <div class="dropdown-title">Hello {{ auth()->user()->name }}</div>
                    <div class="dropdown-divider"></div>
                    <div class="selectgroup layout-color px-4 pt-1">
                        <label class="selectgroup-item">
                            <input type="radio" name="value" value="1"
                                class="selectgroup-input-radio select-layout" checked>
                            <span class="selectgroup-button">Day</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="value" value="2"
                                class="selectgroup-input-radio select-layout">
                            <span class="selectgroup-button">Night</span>
                        </label>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="index.html"> <img alt="image" src="{{ asset('logo.png') }}" class="header-logo" />

                </a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Main</li>
                @can('dashboard-view')
                    <li class="dropdown {{ request()->is('dashboard') ? 'active' : '' }} ">
                        <a href="/dashboard" class="nav-link">
                            <i data-feather="monitor"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endcan
                @can('slider-images')
                    <li class="dropdown {{ request()->is('slider-images') ? 'active' : '' }} ">
                        <a href="/slider-images" class="nav-link">
                            <i data-feather="image"></i>
                            <span>Slider Images</span>
                        </a>
                    </li>
                @endcan
                @can('users')
                    <li class=" {{ request()->is('users**') ? 'active' : '' }} ">
                        <a class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>Users</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ request()->is('users') ? 'active' : '' }} "><a href="{{ route('users.index') }}"
                                    class="nav-link">
                                    Users </a></li>
                            <li class="{{ request()->is('users/withdraw-details') ? 'active' : '' }} ">
                                <a href="{{ route('users.withdraw-details.index') }}" class="nav-link">
                                    Withdraw Details
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('app-data')
                    <li class=" {{ request()->is('app-data**') ? 'active' : '' }} ">
                        <a href="{{ route('app-data.index') }}" class="nav-link">
                            <i data-feather="database"></i>
                            <span>App Data</span>
                        </a>
                    </li>
                    <li class=" {{ request()->is('payment-getway-setting**') ? 'active' : '' }} ">
                        <a href="{{ route('payment-getway-setting.index') }}" class="nav-link">
                            <i data-feather="credit-card"></i>
                            <span>Payment Getway Setting</span>
                        </a>
                    </li>
                @endcan
                @can('chats-view')
                <li class=" {{ request()->is('chats*') ? 'active' : '' }} ">
                    <a href="{{ route('chats.index') }}" class="nav-link">
                        <i data-feather="message-square"></i>
                        Chats <span>
                            <p class="badge badge-danger" id="unreadChatsCount">
                                {{ $countUnreadChats }}
                            </p>
                        </span>
                    </a>
                </li>
                @endcan

                @if (!$enableDesawarOnly)
                    @can('markets')
                        <li class=" {{ request()->is('markets**') ? 'active' : '' }} ">
                            <a class="menu-toggle nav-link has-dropdown">
                                <i data-feather="layers"></i><span>Markets</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="{{ request()->is('markets') ? 'active' : '' }} ">
                                    <a href="{{ route('markets.index') }}" class="nav-link">
                                        Markets
                                    </a>
                                </li>
                                <li class="{{ request()->is('markets/records') ? 'active' : '' }} ">
                                    <a href="{{ route('markets.records') }}" class="nav-link">
                                        Records
                                    </a>
                                </li>
                                <li class="{{ request()->is('markets/results') ? 'active' : '' }} ">
                                    <a href="{{ route('markets.results') }}" class="nav-link">
                                        Results
                                    </a>
                                </li>
                                <li class="{{ request()->is('markets/prediction-results') ? 'active' : '' }} ">
                                    <a href="{{ route('markets.prediction-results.index') }}" class="nav-link">
                                        Prediction
                                    </a>
                                </li>
                                <li class="{{ request()->is('markets/win-history') ? 'active' : '' }} ">
                                    <a href="{{ route('markets.win-history') }}" class="nav-link">
                                        Win History
                                    </a>
                                </li>
                                <li class="{{ request()->is('markets/data') ? 'active' : '' }} ">
                                    <a href="{{ route('markets.data') }}" class="nav-link">
                                        Data
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('startLine')
                        <li class=" {{ request()->is('start-line-markets**') ? 'active' : '' }} ">
                            <a class="menu-toggle nav-link has-dropdown">
                                <i data-feather="layers"></i><span>Start Line Markets</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="{{ request()->is('start-line-markets') ? 'active' : '' }} ">
                                    <a href="{{ route('start-line-markets.index') }}" class="nav-link">
                                        Markets
                                    </a>
                                </li>
                                <li class="{{ request()->is('start-line-markets/records') ? 'active' : '' }} ">
                                    <a href="{{ route('start-line-markets.records') }}" class="nav-link">
                                        Records
                                    </a>
                                </li>
                                <li class="{{ request()->is('start-line-markets/results') ? 'active' : '' }} ">
                                    <a href="{{ route('start-line-markets.results') }}" class="nav-link">
                                        Results
                                    </a>
                                </li>
                                <li class="{{ request()->is('start-line-markets/prediction-results') ? 'active' : '' }} ">
                                    <a href="{{ route('start-line-markets.prediction-results.index') }}"
                                        class="nav-link">
                                        Prediction
                                    </a>
                                </li>
                                <li class="{{ request()->is('start-line-markets/win-history') ? 'active' : '' }} ">
                                    <a href="{{ route('start-line-markets.win-history') }}" class="nav-link">
                                        Win History
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                @endif
                @if ($enableDesawar)
                    @can('desawar')
                        <li class=" {{ request()->is('desawar**') ? 'active' : '' }} ">
                            <a class="menu-toggle nav-link has-dropdown">
                                <i data-feather="layers"></i><span>Desawar Markets</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="{{ request()->is('desawar-markets') ? 'active' : '' }} ">
                                    <a href="{{ route('desawar-markets.index') }}" class="nav-link">
                                        Markets
                                    </a>
                                </li>
                                <li class="{{ request()->is('desawar-markets/chart') ? 'active' : '' }} ">
                                    <a href="{{ route('desawar-markets.chart') }}" class="nav-link">
                                        Charts
                                    </a>
                                </li>
                                <li class="{{ request()->is('desawar-markets/records') ? 'active' : '' }} ">
                                    <a href="{{ route('desawar-markets.records') }}" class="nav-link">
                                        Records
                                    </a>
                                </li>
                                <li class="{{ request()->is('desawar-markets/results') ? 'active' : '' }} ">
                                    <a href="{{ route('desawar-markets.results') }}" class="nav-link">
                                        Results
                                    </a>
                                </li>
                                <li class="{{ request()->is('desawar-markets/prediction-results') ? 'active' : '' }} ">
                                    <a href="{{ route('desawar-markets.prediction-results.index') }}" class="nav-link">
                                        Prediction
                                    </a>
                                </li>
                                <li class="{{ request()->is('desawar-markets/win-history') ? 'active' : '' }} ">
                                    <a href="{{ route('desawar-markets.win-history') }}" class="nav-link">
                                        Win History
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                @endif
                @can('game-types')
                    <li class=" {{ request()->is('game-types**') ? 'active' : '' }} ">
                        <a href="{{ route('game-types.index') }}" class="nav-link">
                            <i data-feather="play"></i><span>Game Types</span>
                        </a>
                    </li>
                @endcan
                @can('profit-loss')
                    <li class=" {{ request()->is('profit-loss**') ? 'active' : '' }} ">
                        <a href="{{ route('profit-loss.index') }}" class="nav-link">
                            <i data-feather="trending-up"></i><span>Profit & Loss</span>
                        </a>
                    </li>                    
                @endcan
                @can('transactions')
                    <li class=" {{ request()->is('transactions**') ? 'active' : '' }} ">
                        <a href="{{ route('transactions.index') }}" class="nav-link">
                            <i data-feather="dollar-sign"></i><span>Transactions</span>
                        </a>
                    </li>
                @endcan
                @can('notifications')
                    <li class=" {{ request()->is('notifications') ? 'active' : '' }} ">
                        <a href="{{ route('notifications.index') }}" class="nav-link">
                            <i data-feather="bell"></i><span>Notfications</span>
                        </a>
                    </li>
                @endcan
                @can('sub-admins')
                    <li class=" {{ request()->is('sub-admins**') ? 'active' : '' }} ">
                        <a href="{{ route('sub-admins.index') }}" class="nav-link">
                            <i data-feather="user-plus"></i><span>Sub Admins</span>
                        </a>
                    </li>
                @endcan
                @can('withdraw-history')
                    <li class=" {{ request()->is('withdraw-history**') ? 'active' : '' }} ">
                        <a href="{{ route('withdraw-history.index') }}" class="nav-link">
                            <i data-feather="book-open"></i>
                            <span>
                                Withdraw-History
                                <p class="badge badge-danger ">
                                    {{ $pendingWithdrawsCount }}
                                </p>
                            </span>

                        </a>
                    </li>
                @endcan
                @can('deposit-history')
                    <li class=" {{ request()->is('deposit-history**') ? 'active' : '' }} ">
                        <a href="{{ route('deposit-history.index') }}" class="nav-link">
                            <i data-feather="book-open"></i>
                            <span>
                                Deposit History
                                <p class="badge badge-danger ">
                                    {{ $pendingDepositsCount }}
                                </p>
                            </span>
                        </a>
                    </li>
                @endcan
                @can('change-password')
                    <li class=" {{ request()->is('change-password**') ? 'active' : '' }} ">
                        <a href="{{ route('change-password.index') }}" class="nav-link">
                            <i data-feather="lock"></i><span>Change Password</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </aside>
    </div>
</div>
