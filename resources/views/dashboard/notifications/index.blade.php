@extends('layouts.app')
@section('title', 'Admin | Notifications')
@section('content')
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Create Notification</h4>
                                    </div>
                                    <form method="post" action="{{ route('notifications.store') }}">
                                        @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <div class="input-group">
                                                </div>
                                                <input type="text" name="title" class="form-control "
                                                    value="{{ old('title') }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <div class="input-group">
                                                </div>
                                                <textarea type="text" name="description" class="form-control " value="{{ old('description') }}"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Notification Redirect Url</label>
                                                <div class="input-group">
                                                </div>
                                                <input type="text" name="url" class="form-control "
                                                    value="{{ old('url') }}">
                                            </div>                                            
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body p-2">
                                        <div class="table-responsive">
                                            <table data-searching="false" data-paging="false" data-info="false"
                                                data-order='[[2, "desc"], [0, "desc"]]' id="myTable"
                                                class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Url</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($notifications as $notification)
                                                        <tr>
                                                            <td>{{ $notification->id }}</td>
                                                            <td>{{ $notification->title }}</td>
                                                            <td>{{ $notification->description }}</td>
                                                            <td>{{ $notification->url }}</td>
                                                            <td>{{ $notification->created_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="m-2" id="pagination">
                                            {{ $notifications->links('pagination.custom') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </section>
            </div>
        </div>
    </div>
@endsection
