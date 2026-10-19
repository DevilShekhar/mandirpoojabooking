<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Sanatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SanatanEventsController extends Controller
{
    public function index(Request $request)
    {
        $query = Sanatan::where('status', 1);

        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        $sanatans = $query->latest()->paginate(9)->withQueryString();

        $programTypes = Sanatan::where('status', 1)
            ->whereNotNull('program_type')
            ->where('program_type', '!=', '')
            ->distinct()
            ->orderBy('program_type')
            ->pluck('program_type');

        $creatorIds = Sanatan::where('status', 1)
            ->whereNotNull('created_by')
            ->distinct()
            ->pluck('created_by');

        $creators = User::whereIn('id', $creatorIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        $usersById = User::whereIn(
            'id',
            $sanatans->getCollection()
                ->pluck('created_by')
                ->filter()
                ->unique()
        )->get(['id', 'name'])->keyBy('id');

        $sanatans->getCollection()->transform(
            function ($sanatan) use ($usersById) {
                $sanatan->languages = $this->decodeLanguages(
                    $sanatan->language
                );

                $sanatan->creator_name =
                    $usersById->get($sanatan->created_by)?->name ?? 'N/A';

                return $sanatan;
            }
        );

        if ($request->ajax()) {
            return response()->json([
                'events' => $sanatans->getCollection()->map(
                    function ($sanatan) {
                        return [
                            'id' => $sanatan->id,
                            'name' => $sanatan->name,
                            'slug' => $sanatan->slug,
                            'program_type' => $sanatan->program_type,

                            'short_description' => $sanatan->short_description
                                ?: Str::limit(
                                    strip_tags($sanatan->description ?? ''),
                                    130
                                ),

                            'image' => $sanatan->banner_image
                                ? asset('storage/' . $sanatan->banner_image)
                                : (
                                    $sanatan->image
                                        ? asset('storage/' . $sanatan->image)
                                        : null
                                ),

                            'duration' => $sanatan->duration,

                            'price' => number_format(
                                (float) $sanatan->price,
                                2,
                                '.',
                                ''
                            ),

                            'languages' => $sanatan->languages,
                            'creator_name' => $sanatan->creator_name,

                            'detail_url' => route(
                                'frontend.sanatanevents.show',
                                $sanatan->slug
                            ),

                            'buy_url' => route(
                                'frontend.sanatanevents.buy',
                                $sanatan->slug
                            ),
                        ];
                    }
                )->values(),

                'pagination' => [
                    'current_page' => $sanatans->currentPage(),
                    'last_page' => $sanatans->lastPage(),
                    'total' => $sanatans->total(),
                    'from' => $sanatans->firstItem(),
                    'to' => $sanatans->lastItem(),
                ],
            ]);
        }

        return view(
            'frontend.sanatanevents.index',
            compact('sanatans', 'programTypes', 'creators')
        );
    }

    public function buy(string $slug)
    {
        $sanatan = Sanatan::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $checkoutUrl = route(
            'frontend.sanatanevents.checkout',
            $sanatan->slug
        );

        if (!Auth::check()) {
            return redirect()
                ->guest($checkoutUrl);
        }

        return redirect()->to($checkoutUrl);
    }

    public function type(Request $request, string $typeSlug)
    {
        $programTypes = Sanatan::where('status', 1)
            ->whereNotNull('program_type')
            ->where('program_type', '!=', '')
            ->distinct()
            ->pluck('program_type');

        $programType = $programTypes->first(
            function ($type) use ($typeSlug) {
                return Str::slug($type) === $typeSlug;
            }
        );

        abort_if(!$programType, 404);

        $query = Sanatan::where('status', 1)
            ->where('program_type', $programType);

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        $sanatans = $query->latest()->paginate(12)->withQueryString();

        $creatorIds = Sanatan::where('status', 1)
            ->where('program_type', $programType)
            ->whereNotNull('created_by')
            ->distinct()
            ->pluck('created_by');

        $creators = User::whereIn('id', $creatorIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        $usersById = User::whereIn(
            'id',
            $sanatans->getCollection()
                ->pluck('created_by')
                ->filter()
                ->unique()
        )->get(['id', 'name'])->keyBy('id');

        $sanatans->getCollection()->transform(
            function ($sanatan) use ($usersById) {
                $sanatan->languages = $this->decodeLanguages(
                    $sanatan->language
                );

                $sanatan->creator_name =
                    $usersById->get($sanatan->created_by)?->name ?? 'N/A';

                return $sanatan;
            }
        );

        return view(
            'frontend.sanatanevents.type',
            compact('sanatans', 'programType', 'creators')
        );
    }

    public function show(string $slug)
    {
        $sanatan = Sanatan::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $sanatan->languages = $this->decodeLanguages(
            $sanatan->language
        );

        $relatedSanatans = Sanatan::where('status', 1)
            ->where('id', '!=', $sanatan->id)
            ->where('program_type', $sanatan->program_type)
            ->latest()
            ->take(3)
            ->get();

        foreach ($relatedSanatans as $relatedSanatan) {
            $relatedSanatan->languages = $this->decodeLanguages(
                $relatedSanatan->language
            );
        }

        return view(
            'frontend.sanatanevents.show',
            compact('sanatan', 'relatedSanatans')
        );
    }

    private function decodeLanguages($language): array
    {
        if (is_array($language)) {
            return $language;
        }

        if (!is_string($language) || trim($language) === '') {
            return [];
        }

        $decoded = json_decode($language, true);

        return is_array($decoded) ? $decoded : [$language];
    }
    public function checkout(string $slug)
    {
        $sanatan = Sanatan::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $sanatan->languages = $this->decodeLanguages(
            $sanatan->language
        );

        return view(
            'frontend.sanatanevents.checkout',
            compact('sanatan')
        );
    }
}
