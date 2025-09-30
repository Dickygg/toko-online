<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
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

    public function formkategori()
    {
        return view('backend.v-kategori.formkategori', [
            'judul' => 'Cetak Laporan Kategori'
        ]);
    }

    public function cetakLaporanKategori(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal'
        ], [
            'tanggal_awal.required' => 'Tanggal Harus Diisi!',
            'tanggal_akhir.required' => 'Tanggal Harus Diisi!',
            'tanggal_akhir.after_or_equal' => 'Tanggal Harus Sama Atau Lebih Dari Tanggal Awal!'
        ]);

        $tanggalawal = Carbon::parse($request->input('tanggal_awal'))->startOfDay();
        $tanggalakhir = Carbon::parse($request->input('tanggal_akhir'))->endOfDay();

        $query = kategori::whereBetween('created_at', [$tanggalawal, $tanggalakhir])->orderBy('id', 'desc');
        $kategori = $query->get();


        return view('backend.v-hasilLaporan.cetakKategori', [
            'judul' => 'Laporan Kategori',
            'tanggalAwal' => $tanggalawal,
            'tanggalAkhir' => $tanggalakhir,
            'cetak' => $kategori
        ]);
    }
}
