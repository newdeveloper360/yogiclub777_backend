<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderImageController extends Controller
{
    public function index()
    {
        $sliderImages = SliderImage::get();
        return view('dashboard.slider-images.index', compact('sliderImages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sliderImage'  => 'required|image',
        ]);
        $path = Storage::disk('public')->put('slider-images', $request->sliderImage);
        SliderImage::create([
            'url' => Storage::url($path)
        ]);
        return back()->with('success', 'Image has been inserted');
    }

    public function destroy($id)
    {
        $sliderImage = SliderImage::findOrFail($id);
        $oldFeaturedImage = $sliderImage->url;
        Storage::disk('public')->delete(str_replace('/storage/', '', $oldFeaturedImage));
        $sliderImage->delete();
        return back()->with('success', 'Image has been deleted');
    }
}
