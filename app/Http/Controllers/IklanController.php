<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Models\Iklan;

class IklanController extends Controller
{
    public function index()
    {
        $iklans = Iklan::orderBy('created_at', 'desc')->get();
        return view('admin.iklan.index', compact('iklans'));
    }

    // public function create()
    // {
    //     return view('admin.iklan.create');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:100',
            'image' => 'required|image|max:5120',
            'link' => 'nullable|url'
        ]);

        $imageName = time() . '_' . preg_replace('/[^a-z0-9\.\-]/i', '_', $request->file('image')->getClientOriginalName());
        // store on the public disk (storage/app/public/iklan)
        Storage::disk('public')->putFileAs('iklan', $request->file('image'), $imageName);

        Iklan::create([
            'section' => $request->section,
            'image_path' => 'iklan/' . $imageName,
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
            if ($iklan->image_path && Storage::disk('public')->exists($iklan->image_path)) {
                Storage::disk('public')->delete($iklan->image_path);
            }
            $imageName = time() . '_' . preg_replace('/[^a-z0-9\.\-]/i', '_', $request->file('image')->getClientOriginalName());
            Storage::disk('public')->putFileAs('iklan', $request->file('image'), $imageName);
            $iklan->image_path = 'iklan/' . $imageName;
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
        if ($iklan->image_path && Storage::disk('public')->exists($iklan->image_path)) {
            Storage::disk('public')->delete($iklan->image_path);
        }
        $iklan->delete();
        return redirect()->route('admin.iklan')->with(['status' => 'success', 'message' => 'Iklan dihapus.']);
    }
}
