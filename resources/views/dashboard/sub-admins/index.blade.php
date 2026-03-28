@extends('layouts.app')
@section('title', 'Admin | SubAdmins ')
@section('content')
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="row">
                        <div class="col-12">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-header">
                                    <h4>Sub Admins</h4>
                                    <div class="card-header-form">
                                        <div class="input-group">
                                            @can('create-markets')
                                                <div class="ml-5 form-group">
                                                    <a href="{{ route('sub-admins.create') }}"
                                                        class="btn btn-outline-primary">Create</a>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive">
                                        <table data-searching="false" data-paging="false" data-info="false"
                                            data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                            class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Bocked</th>
                                                    <th>Permissions</th>
                                                    @can('sub-admins.toogle-blocked.change')
                                                        <th>Action</th>
                                                    @endcan
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($subAdmins as $user)
                                                    <tr>
                                                        <td>{{ $user->id }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->phone }}</td>
                                                        @can('sub-admins.toogle-blocked.change')
                                                            <td>
                                                                <div class="mt-3">
                                                                    <div class="selectgroup ">
                                                                        <label class="selectgroup-item">
                                                                            <input type="radio" value="1"
                                                                                name="blocked{{ $user->id }}"
                                                                                class="selectgroup-input-radio"
                                                                                @if ($user->blocked == 1) checked @endif
                                                                                onchange="toogleBlock({{ $user->id }},1)">
                                                                            <span class="selectgroup-button">Blocked </span>
                                                                        </label>
                                                                        <label class="selectgroup-item">
                                                                            <input type="radio"
                                                                                name="blocked{{ $user->id }}" value="0"
                                                                                class="selectgroup-input-radio"
                                                                                @if ($user->blocked == 0) checked @endif
                                                                                onchange="toogleBlock({{ $user->id }},0)">
                                                                            <span class="selectgroup-button">Unblock</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @endcan
                                                        <td><a class="btn btn-primary"
                                                                href="{{ route('sub-admins.edit-permissions', ['id' => $user->id]) }}">Edit
                                                            </a></td>
                                                        <td><a href="{{ route('sub-admins.edit', ['id' => $user->id]) }}"
                                                                class="btn btn-outline-primary">Edit</a>|<a
                                                                href="{{ route('sub-admins.delete', ['id' => $user->id]) }}"
                                                                class="btn btn-outline-primary">Delete</a>
                                                        </td>
                                                        <td>{{ $user->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script>
        function toogleBlock(id, block) {
            $.ajax({
                url: "{{ route('sub-admins.toogle-blocked.change') }}",
                type: "POST",
                data: {
                    user_id: id,
                    blocked: block
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                        .attr('content')
                },
            })
        }
    </script>
@endsection
