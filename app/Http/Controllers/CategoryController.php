<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //index
    public function index()
    {
        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }


    //create
    public function create()
    {
        return view('pages.categories.create')->with('success', 'Category created successfully');
    }

    //store
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'name' => 'required|string|max:255',
            // Hapus validasi image
        ]);

        // Menyimpan data kategori ke dalam database
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;

        // Simpan kategori
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    //show
    public function show($id)
    {
        return view('pages.categories.show');
    }


    //edit
    public function edit($id)
    {
        $category = Category::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    //update
    public function update(Request $request, $id)
    {
        // Validasi request...
        $request->validate([
            'name' => 'required',
            //'image' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // Update kategori...
        $category = Category::find($id);
        $category->name = $request->input('name');
        $category->description = $request->input('description');

        // Cek apakah ada file gambar yang diunggah
        // if ($request->hasFile('image')) {
        //    $image = $request->file('image');
        //  $imagePath = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
        //  $image->storeAs('public/categories/', $category->id . '.' . $image->getClientOriginalExtension());
        //  $category->image = $imagePath;
        //  }

        // Simpan perubahan
        $category->save();

        // Redirect kembali ke halaman kategori dengan pesan sukses
        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        // Temukan kategori berdasarkan ID
        $category = Category::findOrFail($id);

        // Cek apakah kategori memiliki produk terkait
        if ($category->products()->count() > 0) {
            // Jika ada produk terkait, arahkan kembali dengan pesan error
            return redirect()->route('categories.index')
                ->with('error', 'Tidak dapat menghapus kategori karena masih ada produk yang terkait.');
        }

        // Jika tidak ada produk terkait, hapus kategori
        $category->delete();

        // Arahkan kembali dengan pesan sukses
        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
