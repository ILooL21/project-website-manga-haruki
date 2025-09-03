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

        $imageName = time() . '_' . preg_replace('/[^a-z0-9\.\-]/i', '_', $request->file('image')->getClientOriginalName());
        $uploaded = Cloudinary::uploadApi()->upload($request->file('image')->getRealPath(), [
            'folder' => 'iklan',
            'public_id' => pathinfo($imageName, PATHINFO_FILENAME),
            'resource_type' => 'image'
        ]);

        Iklan::create([
            'section' => $request->section,
            'image_path' => $uploaded['public_id'],
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
            // delete old file on public disk if exists
            if ($iklan->image_path) {
                Cloudinary::uploadApi()->destroy($iklan->image_path);
            }
            $imageName = time() . '_' . preg_replace('/[^a-z0-9\.\-]/i', '_', $request->file('image')->getClientOriginalName());
            $uploaded = Cloudinary::uploadApi()->upload($request->file('image')->getRealPath(), [
                'folder' => 'iklan',
                'public_id' => pathinfo($imageName, PATHINFO_FILENAME),
                'resource_type' => 'image'
            ]);
            $iklan->image_path = $uploaded['public_id'];
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
        if ($iklan->image_path) {
            Cloudinary::uploadApi()->destroy($iklan->image_path);
        }
        $iklan->delete();
        return redirect()->route('admin.iklan')->with(['status' => 'success', 'message' => 'Iklan dihapus.']);
    }
}
