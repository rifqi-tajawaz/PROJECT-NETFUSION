<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Models\DocumentationPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\DocumentationCategory;

class DocumentationController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentationPage::with(['parent', 'category'])->latest();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $pages = $query->paginate(10);
        return view('admin.support.documentation.index', compact('pages'));
    }

    public function create()
    {
        // Allow any page to be a parent (Generic Tree)
        $parents = DocumentationPage::all();
        $categories = DocumentationCategory::orderBy('order')->get();
        return view('admin.support.documentation.create', compact('parents', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:documentation_pages,id',
            'category_id' => 'nullable|exists:documentation_categories,id',
            'slug' => 'nullable|string|unique:documentation_pages,slug',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['is_published'] = $request->has('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        DocumentationPage::create($data);

        return redirect()->route('admin.support.documentation.index')->with('success', 'Page created successfully');
    }

    public function edit($id)
    {
        $page = DocumentationPage::findOrFail($id);
        // exclude self to avoid infinite loop
        $parents = DocumentationPage::where('id', '!=', $id)->get();
        $categories = DocumentationCategory::orderBy('order')->get();
        return view('admin.support.documentation.edit', compact('page', 'parents', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $page = DocumentationPage::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:documentation_pages,id',
            'category_id' => 'nullable|exists:documentation_categories,id',
            'slug' => 'nullable|string|unique:documentation_pages,slug,' . $id,
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['is_published'] = $request->has('is_published');
        $data['published_at'] = $data['is_published'] ? ($page->published_at ?? now()) : null;

        $page->update($data);

        return redirect()->route('admin.support.documentation.index')->with('success', 'Page updated successfully');
    }

    public function destroy($id)
    {
        $page = DocumentationPage::findOrFail($id);
        $page->delete();
        return redirect()->route('admin.support.documentation.index')->with('success', 'Page deleted successfully');
    }

    /**
     * Handle Image Upload from Editor
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Move to public/uploads/documentation
            $image->move(public_path('uploads/documentation'), $imageName);

            $url = asset('uploads/documentation/' . $imageName);

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }
}
