@extends('layouts.app')
@section('title','Admin | SubAdmins Permissions')
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
                                @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif   <div class="card">
                                    <div class="card-header">
                                        <h4>Sub Admin Update Permissions </h4>
                                    </div>
                                    <form method="post"
                                        action="{{ route('sub-admins.update-permissions', ['id' => $subAdmin->id]) }}">
                                            @csrf
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="d-block">Permissions</label>
                                                    <div class="row">
                                                        @foreach ($permissions as $permission)
                                                            <div class="ml-5 col-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="permissions[]" value="{{ $permission->id }}"
                                                                    @isset($subAdmin)
                                                                    @if ($subAdmin->permissions->contains('id', $permission->id)) 
                                                                        checked 
                                                            @endif>
                                                            @endisset
                                                                    <label class="form-check-label"
                                                                    for="{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-outline-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
