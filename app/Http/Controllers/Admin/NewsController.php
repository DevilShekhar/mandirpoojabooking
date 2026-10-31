<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Str;
class NewsController extends Controller
{
    private function isAdmin(): bool
    {
        return Auth::user()->hasAnyRole(['Admin', 'Super Admin']);
    }
    private function isTempleManager(): bool
    {
        return Auth::user()->hasRole('Temple Manager');
    }
    private function assignedTempleIds()
    {
        return DB::table('temple_user')
            ->where('user_id', Auth::id())
            ->where('approval_status', 'approved')
            ->pluck('temple_id');
    }
    private function checkAccess(): void
    {
        if ($this->isAdmin()) {
            return;
        }
        abort_unless($this->isTempleManager(), 403, 'Unauthorized access.');
        abort_if($this->assignedTempleIds()->isEmpty(),403,'No approved temple assigned to this user.');
    }
    private function accessibleTemples()
    {
        $this->checkAccess();
        if ($this->isAdmin()) {
            return Temple::orderBy('name')->get();
        }
        return Temple::whereIn('id', $this->assignedTempleIds())->orderBy('name')->get();
    }

    private function newsQuery()
    {
        $this->checkAccess();
        $query = News::with('temple');
        if (!$this->isAdmin()) {
            $query->whereIn('temple_id', $this->assignedTempleIds());
        }
        return $query;
    }
    private function validateTemple(Request $request): int
    {
        $this->checkAccess();
        if ($this->isAdmin()) {
            $validated = $request->validate([
                'temple_id' => [
                    'required',
                    'integer',
                    Rule::exists('temples', 'id'),
                ],
            ]);
            return (int) $validated['temple_id'];
        }
        $templeIds = $this->assignedTempleIds();
        if ($templeIds->count() === 1) {
            return (int) $templeIds->first();
        }
        $validated = $request->validate([
            'temple_id' => [
                'required',
                'integer',
                Rule::exists('temples', 'id'),
            ],
        ]);
        abort_unless($templeIds->contains((int) $validated['temple_id']), 403, 'You cannot select this temple.' );
        return (int) $validated['temple_id'];
    }
    private function uploadImage(Request $request, string $field, string $folder): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }
        return $request->file($field)->store($folder, 'public');
    }
    public function index()
    {
        $news = $this->newsQuery()->latest()->get();
        return view('admin.news.index', compact('news'));
    }
    public function create()
    {
        $temples = $this->accessibleTemples();
        $selectedTempleId = null;
        if ($this->isTempleManager() && $temples->count() === 1) {
            $selectedTempleId = $temples->first()->id;
        }
        return view('admin.news.create', compact('temples','selectedTempleId'));
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'artical_img' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['required', 'in:0,1'],
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['temple_id'] = $this->validateTemple($request);
        $validated['image'] = $this->uploadImage($request, 'image', 'news');
        $validated['artical_img'] = $this->uploadImage($request, 'artical_img', 'news/articles');
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();
        News::create($validated);
        return redirect()->route('my-news.index')->with('success', 'News created successfully.');
    }
    public function edit(News $my_news)
    {
        $this->checkAccess();
        if (!$this->isAdmin()) {
            abort_unless($this->assignedTempleIds()->contains((int) $my_news->temple_id), 403, 'You cannot edit this news.');
        }
        $temples = $this->accessibleTemples();
        $selectedTempleId = $my_news->temple_id;
        return view('admin.news.edit', compact('my_news','temples','selectedTempleId'));
    }

    public function update(Request $request, News $my_news)
    {
        $this->checkAccess();
        if (!$this->isAdmin()) {
            abort_unless($this->assignedTempleIds()->contains((int) $my_news->temple_id), 403, 'You cannot update this news.');
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'artical_img' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'status' => ['required', 'in:0,1'],
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $validated['temple_id'] = $this->validateTemple($request);
        if ($request->hasFile('image')) {
            if ($my_news->image) {
                Storage::disk('public')->delete($my_news->image);
            }
            $validated['image'] = $this->uploadImage($request, 'image', 'news');
        }
        if ($request->hasFile('artical_img')) {
            if ($my_news->artical_img) {
                Storage::disk('public')->delete($my_news->artical_img);
            }
            $validated['artical_img'] = $this->uploadImage($request, 'artical_img', 'news/articles');
        }
        $validated['updated_by'] = Auth::id();
        $my_news->update($validated);
        return redirect()->route('my-news.index')->with('success', 'News updated successfully.');
    }
    public function show(News $my_news)
    {
        $this->checkAccess();

        if (!$this->isAdmin()) {
            abort_unless(
                $this->assignedTempleIds()->contains((int) $my_news->temple_id),
                403,
                'You cannot view this news.'
            );
        }

        $my_news->load(['temple', 'createdBy', 'updatedBy']);

        return view('admin.news.show', compact('my_news'));
    }

    public function destroy(News $my_news)
    {
        $this->checkAccess();
        if (!$this->isAdmin()) {
            abort_unless($this->assignedTempleIds()->contains((int) $my_news->temple_id),403,'You cannot delete this news.');
        }
        $my_news->update(['status' => 0,'updated_by' => Auth::id(),]);
        return redirect()->route('my-news.index')->with('success', 'News deactivated successfully.');
    }
}