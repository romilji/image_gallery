<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    public function index(Request $request)
    {
            $images = Image::all();
            // dd($images);
            return view('gallery', compact('images'));
    }

    public function store(Request $request)
    {
        $images = $request->file('file');
        $imageUrls = [];

        foreach ($images as $image) {
            $path = $image->store('uploads', 'public');
            $imageUrl = Storage::url($path);

            // Save image details to the database
            $img = Image::create([
                'image_url' => $imageUrl,
            ]);

            $imageUrls[] = $img;
        }

        return response()->json(['images' => $imageUrls]);
    }

    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        $image->update($request->only('title', 'tag'));
        
        return response()->json(['image' => $image]);
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        Storage::disk('public')->delete($image->image_url);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
