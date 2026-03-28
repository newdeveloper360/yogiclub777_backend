@extends('layouts.app')
@section('title','Admin | Game Types')
@section('content')
    <div class="loader">
    </div>
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
                                        <h4>Game Types </h4>
                                    </div>
                                    <div class="card-body">
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        @foreach ($gameTypes as $gameType)
                                            @if ($gameType->type === 'desawar')
                                                <form action="{{ route('game-types.update', ['id' => $gameType->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="form-group col-3">
                                                            <label>Name</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control "
                                                                    value="{{ $gameType->name }}" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label>Game Type</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control "
                                                                    value="{{ $gameType->game_type }}" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-3">
                                                            <label>Type</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control "
                                                                    value="{{ $gameType->type }}" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-2 ">
                                                            <label>Multiply By</label>
                                                            <div class="input-group">
                                                                <input type="number" name="multiply_by" min="0.1"
                                                                    step="0.1" class="form-control" required
                                                                    value="{{ $gameType->multiply_by }}">
                                                            </div>
                                                        </div>
                                                        @can('update-game-types')
                                                            <div class="form-group col-2 mt-4">
                                                                <button type="submit"
                                                                    class="form-control btn btn-outline-primary">Save</button>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </form>
                                            @endif
                                        
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
