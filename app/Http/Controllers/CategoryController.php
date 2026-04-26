<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the authenticated user's categories.
     */
    public function index(): View
    {
        $categories = auth()->user()
            ->categories()
            ->withCount('transactions')
            ->latest()
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Category::class);

       $validated = $request->validate([
    'name' => [
        'required',
        'string',
        'max:100',
        // Unique within this user's categories only
        \Illuminate\Validation\Rule::unique('categories')
            ->where('user_id', auth()->id()),
    ],
    'description' => ['nullable', 'string', 'max:1000'],
]);


        auth()->user()->categories()->create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);

       $validated = $request->validate([
        'name' => [
        'required',
        'string',
        'max:100',
        // Same uniqueness rule, but IGNORE the current category's own row
        \Illuminate\Validation\Rule::unique('categories')
            ->where('user_id', auth()->id())
            ->ignore($category->id),
    ],
    'description' => ['nullable', 'string', 'max:1000'],
]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        if ($category->transactions()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Cannot delete a category that has transactions. Reassign them first.');
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}