<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\ImageCloudinary as Product;

class ImageCloudinaryController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('image-clodinary.index', compact('products'));
    }

    public function create()
    {
        return view('image-clodinary.create');
    }

    public function store(Request $request)
    {
        $validateRequest = $request->validate([
            'name' => ['required', 'max:255'],
            'image' => ['required', 'image', 'max:2048'],
            'price' => ['required', 'numeric'],
            'description' => 'required',
        ]);
        $file = $request->file('image');
        // upload using Cloudinary facade (storeOnCloudinary is not available on UploadedFile)
        $uploaded = Cloudinary::uploadApi()->upload(
            $file->getRealPath(),
            ['folder' => 'products'] // tùy chọn folder nếu cần
        );

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $uploaded['secure_url'],
            'image_public_id' => $uploaded['public_id'],
        ]);

        return redirect()->route('image.cloudinary.index')->with('message', 'Created successfully');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('image-clodinary.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validateRequest = $request->validate([
            'name' => ['sometimes', 'required', 'max:255'],
            'image' => ['sometimes', 'required', 'image', 'max:2048'],
            'price' => ['sometimes', 'required', 'numeric'],
            'description' => ['sometimes', 'required'],
        ]);

        if ($request->hasFile('image')) {
            // use Cloudinary v2 uploadApi for destroy/upload
            try {
                if (! empty($product->image_public_id)) {
                    Cloudinary::uploadApi()->destroy($product->image_public_id);
                }

                $file = $request->file('image');
                $options = ['folder' => 'products'];
                $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), $options);
                $url = $uploaded['secure_url'] ?? ($uploaded['url'] ?? null);
                $public_id = $uploaded['public_id'] ?? null;

                $product->update([
                    'image_url' => $url,
                    'image_public_id' => $public_id,
                ]);
            } catch (\Exception $e) {
                // log and return with error
                if (function_exists('logger')) {
                    logger('Cloudinary upload failed (update): ' . $e->getMessage());
                }
                return back()->withErrors(['image' => 'Failed to upload new image.']);
            }
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('image.cloudinary.index')->with('message', 'Updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        try {
            if (! empty($product->image_public_id)) {
                Cloudinary::uploadApi()->destroy($product->image_public_id);
            }
        } catch (\Exception $e) {
            if (function_exists('logger')) {
                logger('Cloudinary destroy failed (delete): ' . $e->getMessage());
            }
            // continue and delete local record even if remote deletion fails
        }

        $product->delete();

        return redirect()->route('image.cloudinary.index')->with('message', 'Deleted Successfully');
    }
}
