<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Puja;
use App\Models\PujaFaq;
use App\Models\Temple;
use App\Models\Deity;
use App\Models\PujaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PujaController extends Controller
{
    public function index()
    {
        $pujas = Puja::with([
            'temple',
            'deity',
            'pujaCategory',
            'faqs'
        ])
        ->latest()
        ->get();

        return view('admin.pujas.index', compact('pujas'));
    }

    public function create()
    {
        $temples = Temple::where('status', 1)
            ->latest()
            ->get();

        $deities = Deity::where('status', 1)
            ->latest()
            ->get();

        $pujaCategories = PujaCategory::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.pujas.create', compact(
            'temples',
            'deities',
            'pujaCategories'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'deity_id' => 'required|exists:deities,id',
            'puja_category_id' => 'required|exists:puja_categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'ritual_details' => 'nullable|string',
            'samagri_details' => 'nullable|string',
            'faq' => 'nullable|array',
            'faq.*.question' => 'required|string|max:500',
            'faq.*.answer' => 'required|string',
            'duration' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_online' => 'required|boolean',
            'is_offline' => 'required|boolean',
        ]);

        $lastPuja = Puja::latest('id')->first();

        if ($lastPuja && $lastPuja->puja_code) {
            $lastNumber = (int) str_replace(
                'PUJA-',
                '',
                $lastPuja->puja_code
            );

            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $pujaCode = 'PUJA-' . str_pad(
            $nextNumber,
            5,
            '0',
            STR_PAD_LEFT
        );

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')
                ->store('puja_image', 'public');
        }

        $bannerImage = null;

        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image')
                ->store('puja_banner', 'public');
        }

        $puja = Puja::create([
            'temple_id' => $request->temple_id,
            'deity_id' => $request->deity_id,
            'puja_category_id' => $request->puja_category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'puja_code' => $pujaCode,
            'description' => $request->description,
            'benefits' => $request->benefits,
            'ritual_details' => $request->ritual_details,
            'samagri_details' => $request->samagri_details,
            'duration' => $request->duration,
            'image' => $image,
            'banner_image' => $bannerImage,
            'is_online' => $request->is_online,
            'is_offline' => $request->is_offline,
            'status' => 1,
        ]);

        if ($request->has('faq')) {
            foreach ($request->faq as $index => $faq) {
                if (
                    !empty($faq['question']) &&
                    !empty($faq['answer'])
                ) {
                    PujaFaq::create([
                        'puja_id' => $puja->id,
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $index,
                        'status' => 1,
                    ]);
                }
            }
        }

        return redirect()
            ->route('pujas.index')
            ->with('Success', 'Puja Created Successfully');
    }

    public function edit(Puja $puja)
    {
        $puja->load('faqs');

        $temples = Temple::where('status', 1)
            ->latest()
            ->get();

        $deities = Deity::where('status', 1)
            ->latest()
            ->get();

        $pujaCategories = PujaCategory::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.pujas.edit', compact(
            'puja',
            'temples',
            'deities',
            'pujaCategories'
        ));
    }

    public function update(Request $request, Puja $puja)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'deity_id' => 'required|exists:deities,id',
            'puja_category_id' => 'required|exists:puja_categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'ritual_details' => 'nullable|string',
            'samagri_details' => 'nullable|string',
            'faq' => 'nullable|array',
            'faq.*.question' => 'required|string|max:500',
            'faq.*.answer' => 'required|string',
            'duration' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_online' => 'required|boolean',
            'is_offline' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        $image = $puja->image;
        $bannerImage = $puja->banner_image;

        if ($request->hasFile('image')) {
            if (
                $puja->image &&
                Storage::disk('public')->exists($puja->image)
            ) {
                Storage::disk('public')->delete($puja->image);
            }

            $image = $request->file('image')
                ->store('puja_image', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if (
                $puja->banner_image &&
                Storage::disk('public')->exists($puja->banner_image)
            ) {
                Storage::disk('public')->delete($puja->banner_image);
            }

            $bannerImage = $request->file('banner_image')
                ->store('puja_banner', 'public');
        }

        $puja->update([
            'temple_id' => $request->temple_id,
            'deity_id' => $request->deity_id,
            'puja_category_id' => $request->puja_category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'puja_code' => $puja->puja_code,
            'description' => $request->description,
            'benefits' => $request->benefits,
            'ritual_details' => $request->ritual_details,
            'samagri_details' => $request->samagri_details,
            'duration' => $request->duration,
            'image' => $image,
            'banner_image' => $bannerImage,
            'is_online' => $request->is_online,
            'is_offline' => $request->is_offline,
            'status' => $request->status,
        ]);

        PujaFaq::where('puja_id', $puja->id)->delete();

        if ($request->has('faq')) {
            foreach ($request->faq as $index => $faq) {
                if (
                    !empty($faq['question']) &&
                    !empty($faq['answer'])
                ) {
                    PujaFaq::create([
                        'puja_id' => $puja->id,
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $index,
                        'status' => 1,
                    ]);
                }
            }
        }

        return redirect()
            ->route('pujas.index')
            ->with('Success', 'Puja Updated Successfully');
    }

    public function destroy(Puja $puja)
    {
        $puja->update([
            'status' => 0
        ]);

        return redirect()
            ->route('pujas.index')
            ->with('Success', 'Puja Deleted Successfully');
    }
}