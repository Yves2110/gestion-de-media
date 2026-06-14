<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::idDescending()->paginate(4);

        return view('categoryManage.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['label' => 'required|string|max:120|unique:categories,label']);

        Category::create($request->only('label'));

        return back()->with('message', 'Catégorie ajoutée.');
    }

    public function show(Category $category)
    {
        return view('categoryManage.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('categoryManage.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'label' => 'required|string|max:120|unique:categories,label,' . $category->id,
        ]);

        $category->update($request->only('label'));

        Document::where('category_id', $category->id)->update(['categorie' => $category->label]);

        return redirect()->route('category.index')->with('message', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('category.index')->with('message', 'Catégorie supprimée.');
    }
}
