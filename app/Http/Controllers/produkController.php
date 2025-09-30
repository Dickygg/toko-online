<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;
use App\Models\FotoProduk;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\fileExists;

class produkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::orderBy('updated_at', 'desc')->get();
        return view('backend.v-produk.index', [
            'judul' => 'Data Produk',
            'produk' => $produk
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = kategori::orderBy('id', 'desc')->get();
        return view('backend.v-produk.create', [
            'judul' => 'Tambah Data Produk',
            'kategori' => $kategori
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validateData = $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required|max:225|unique:produk',
            'detail' => 'required',
            'harga' => 'required|integer',
            'berat' => 'required|numeric',
            'stock' => 'required|integer',
            'foto' => 'required|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ], $messages = [
            'foto.image' => 'Format Gambar gunakan file dengan ekstensi jpeg, jpg, png atau gif.',
            'foto.max' =>  'Ukuran file gambar Maksimal adalah 1024 KB.'
        ]);

        $validateData['status'] = 0;
        $validateData['user_id'] = Auth::id(); // ✅ set user_id otomatis dari user login

        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';
            // Simpan gambar asli
            $fileName = ImageHelper::uploadAndRisize($file, $directory, $originalFileName);
            $validateData['foto'] = $fileName;
            // Simpan nama file asli di database
            $validateData['foto'] = $originalFileName;
        }

        Produk::create($validateData, $messages);
        return redirect()->route('backend.produk.index')->with('succes', 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with('fotoProduk')->findOrFail($id);
        $kategori = kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.v-produk.show', [
            'judul' => 'Foto Produk',
            'show' => $produk,
            'kategori' => $kategori

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.v-produk.edit', [
            'judul' => 'Edit Data',
            'produk' => $produk,
            'kategori' => $kategori
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);
        $rules = [
            'nama_produk' => 'required|max:225|unique:produk,nama_produk,' . $id,
            'kategori_id' => 'required',
            'status' => 'required',
            'detail' => 'required',
            'harga' => 'required|integer',
            'berat' => 'required|numeric',
            'stock' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];
        $messages = [
            'foto.image' => 'Format Gambar gunakan file dengan ekstensi jpeg, jpg, png atau gif.',
            'foto.max' =>  'Ukuran file gambar Maksimal adalah 1024 KB.'
        ];
        $validateData = $request->validate($rules, $messages);
        $validateData['user_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            // hapus gambar lama kalau ada
            $oldImagePath = public_path('storage/img-produk/') . $produk->foto;
            if ($produk->foto && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // upload gambar baru
            $foto = $request->file('foto');
            $namaFoto = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('storage/img-produk'), $namaFoto);

            $validateData['foto'] = $namaFoto;
        } else {
            // kalau tidak upload gambar, tetap pakai yang lama
            $validateData['foto'] = $produk->foto;
        }

        $produk->update($validateData);
        return redirect()->route('backend.produk.index')->with('succes', 'Data berhasil DiUpdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        $directory = public_path('storage/img-produk/');

        if ($produk->foto) {
            $oldImagePath = $directory . $produk->foto;
            if (fileExists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        //hapus foto tambahan ditable foto_produk
        $fotoProduks = FotoProduk::where('produk_id', $id)->get();
        foreach ($fotoProduks as $fotoProduk) {
            $fotopath = $directory . $fotoProduk->foto;
            if (file_exists($fotopath)) {
                unlink($fotopath);
            }
            $fotoProduk->delete();
        }
        $produk->delete();
        return redirect()->route('backend.produk.index')->with('success', 'Data Berhasil Dihapus');
    }

    //function menyimpan foto tambahan
    public function storefoto(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'foto_produk.*' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ]);
        if ($request->hasFile('foto_produk')) {
            foreach ($request->file('foto_produk') as $file) {
                // Buat nama file yang unik
                $extension = $file->getClientOriginalExtension();
                $filename = date('YmdHis') . '_' . uniqid() . '.' . $extension;
                $directory = 'storage/img-produk/';
                // Simpan dan resize gambar menggunakan ImageHelper
                ImageHelper::uploadAndRisize($file, $directory, $filename, 800, null);
                // Simpan data ke database
                FotoProduk::create([
                    'produk_id' => $request->produk_id,
                    'foto' => $filename,
                ]);
            }
        }
        return redirect()->route('backend.produk.show', $request->produk_id)
            ->with('success', 'Foto berhasil ditambahkan.');
    }

    //mengapus foto tambahan
    public function destroyfoto($id)
    {
        $foto = FotoProduk::findOrFail($id);
        $produkId = $foto->produk_id;

        // Hapus file gambar dari storage
        $imagePath = public_path('storage/img-produk/') . $foto->foto;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        // Hapus record dari database
        $foto->delete();
        return redirect()->route('backend.produk.show', $produkId)
            ->with('success', 'Foto berhasil dihapus.');
    }
}
