<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Iklan;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class IklanController extends Controller
{
    public function index()
    {
        $iklans = Iklan::orderBy('created_at', 'desc')->get();
        return view('admin.iklan.index', compact('iklans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:100',
            'image' => 'required|image|max:5120',
            'link' => 'nullable|url'
        ]);

        $file = $request->file('image');
        $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
            'folder' => 'iklan',
            'resource_type' => 'image'
        ]);

        Iklan::create([
            'section' => $request->section,
            'image_path' => $uploaded['secure_url'] ?? ($uploaded['url'] ?? null),
            'image_public_id' => $uploaded['public_id'] ?? null,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.iklan')->with(['status' => 'success', 'message' => 'Iklan dibuat.']);
    }

    public function edit($encodedId)
    {
        // decode id
        $id = Crypt::decryptString(urldecode($encodedId));
        $iklan = Iklan::findOrFail($id);
        return view('admin.iklan.edit', compact('iklan'));
    }

    public function update(Request $request, $encodedId)
    {
        $id = Crypt::decryptString(urldecode($encodedId));
        $iklan = Iklan::findOrFail($id);

        $request->validate([
            'section' => 'required|string|max:100',
            'image' => 'nullable|image|max:5120',
            'link' => 'nullable|url'
        ]);

        if ($request->hasFile('image')) {
            // delete old remote image if exists
            if (! empty($iklan->image_public_id)) {
                try {
                    Cloudinary::uploadApi()->destroy($iklan->image_public_id, ["invalidate" => true]);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            $file = $request->file('image');
            $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'iklan',
                'resource_type' => 'image'
            ]);

            $iklan->image_path = $uploaded['secure_url'] ?? ($uploaded['url'] ?? null);
            $iklan->image_public_id = $uploaded['public_id'] ?? null;
        }

        $iklan->section = $request->section;
        $iklan->link = $request->link;
        $iklan->save();

        return redirect()->route('admin.iklan')->with(['status' => 'success', 'message' => 'Iklan diperbarui.']);
    }

    public function destroy($encodedId)
    {
        $id = Crypt::decryptString(urldecode($encodedId));
        $iklan = Iklan::findOrFail($id);
        if (! empty($iklan->image_public_id)) {
            try {
                Cloudinary::uploadApi()->destroy($iklan->image_public_id, ["invalidate" => true]);
            } catch (\Throwable $e) {
                report($e);
            }
        }
        $iklan->delete();
        return redirect()->route('admin.iklan')->with(['status' => 'success', 'message' => 'Iklan dihapus.']);
    }
}
