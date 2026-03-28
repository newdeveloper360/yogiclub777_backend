@extends('layouts.app')
@section('title','Admin | Slider Images')
@section('content')
<div class="loader"></div>
<div id="app">
    <div class="main-wrapper main-wrapper-1">
        @include('layouts.navbar')
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Slider Image </h4>
                                </div>
                                <form action="{{ route('slider-images.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-6">
                                                <label>Slider Images</label>
                                                <div class="input-group">
                                                    <input type="file" name="sliderImage" id="sliderImage" class="form-control" accept="image/*">
                                                </div>
                                                @error('sliderImage')
                                                <div class="alert alert-danger mt-4">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="col-4">
                                                <div id="image-preview"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-primary">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <h3>Slider Images</h3>
            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-flex">
                        @forelse($sliderImages as $img)
                        <div class="m-3">
                            <img src="{{ $img->url }}" alt="" height="200" width="200"><br>
                            <a class="btn btn-danger mt-2" href="{{ route('slider-images.destroy', ['id' => $img->id]) }}">DELETE</a>
                        </div>
                        @empty
                        <p class="mt-2">No Images yet</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const input = document.getElementById('sliderImage');
    const preview = document.getElementById('image-preview');

    input.addEventListener('change', () => {
        preview.innerHTML = '';

        for (const file of input.files) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.height = 200
            img.width = 200
            preview.appendChild(img);
        }
    });
</script>
@endpush