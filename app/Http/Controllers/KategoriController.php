<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Http\Requests\StorekategoriRequest;
// use App\Http\Requests\UpdatekategoriRequest;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = kategori::orderBy('nama_kategori', 'desc')->get();
        return view('backend.v-kategori.index', [
            'judul' => 'Data Ketegori',
            'kategori' => $kategori
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.v-kategori.create', [
            'judul' => 'Tambah Data'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate(
            [

                'nama_kategori' => 'required|max:100|unique:kategori',
            ]
        );

        kategori::create($validateData);
        return redirect()->route('backend.kategori.index')->with('success', 'Kategori Sudah Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(kategori $kategori) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = kategori::findOrFail($id);
        return view('backend.v-kategori.edit', [
            'judul' => 'Edit Data',
            'kategori' => $kategori
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'nama_kategori' => 'required|max:100|unique:kategori,nama_kategori,' . $id,
        ];
        $validateData = $request->validate($rules);
        kategori::where('id', $id)->update($validateData);
        return redirect()->route('backend.kategori.index')->with('succes', 'Data Kategori Berhasil DiUbah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = kategori::findOrFail($id);
        $kategori->delete();
        return redirect()->route('backend.kategori.index')->with('succes', 'Data Berhasil Dihapus!');
    }
}
