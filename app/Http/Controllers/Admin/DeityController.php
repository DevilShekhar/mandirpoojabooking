<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeityController extends Controller
{
    public function index(){
        $deities = Deity::get();
        return view('admin.deities.index',compact('deities'));
    }

    public function create(){
        return view('admin.deities.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $image = null;


        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('deity_image', 'public');
        }

        Deity::create([
            'name'=>$request->name,
            'slug' => Str::slug($request->name),
            'description'=>$request->description,
            'image' => $image,
        ]);
        return redirect()
            ->route('deities.index')
            ->with('Success','Deity Created Successfully');
    }
    public function edit(Deity $deity)
    {
        return view('admin.deities.edit', compact('deity'));
    }

    public function update(Request $request, Deity $deity)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|boolean',
        ]);

        $image = $deity->image;

        if ($request->hasFile('image')) {
            if ($deity->image && Storage::disk('public')->exists($deity->image)) {
                Storage::disk('public')->delete($deity->image);
            }

            $image = $request->file('image')->store('deity_image', 'public');
        }

        $deity->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $image,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('deities.index')
            ->with('Success', 'Deity Updated Successfully');
    }
    public function destroy(Deity $deity)
    {
        $deity->update([
            'status' => 0,
        ]);

        return redirect()
            ->route('deities.index')
            ->with('Success', 'Deity Deleted Successfully');
    }
}
