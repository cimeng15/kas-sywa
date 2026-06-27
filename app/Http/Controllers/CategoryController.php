<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories()->latest()->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        auth()->user()->categories()->create($request->validate($this->rules()));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $category = auth()->user()->categories()->findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = auth()->user()->categories()->findOrFail($id);
        $category->update($request->validate($this->rules()));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $category = auth()->user()->categories()->findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
        ];
    }
}
