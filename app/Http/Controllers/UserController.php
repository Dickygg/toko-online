<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('updated_at', 'desc')->get();
        return view('backend.v-user.index', [
            'judul' => 'Data User',
            'index' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('backend.v-user.create', [
            'judul' => 'Tambah User'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate(
            [
                'nama' => 'required|max:255',
                'email' => 'required|max:255|email|unique:user',
                'role' => 'required',
                'hp' => 'required|min:10|max:13',
                'password' => 'required|min:4|confirmed',
                'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
            ],
            $messages = [
                'foto.image' => 'Mohon Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
                'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'

            ]
        );
        $validateData['status'] = 0;

        //menggunakan image helper
        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';
            //simpan gambar dengan ukuran yang ditentukan
            ImageHelper::uploadAndRisize($file, $directory, $originalFileName, 385, 400);
            // isi null jika ingin otomatis ukurannya
            //simpan nama file asli didatabase
            $validateData['foto'] = $originalFileName;
        }

        //password hash
        $password = $request->input('password');
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';
        // huruf kecil ([a-z]), huruf besar ([A-Z]), dan angka (\d) (?=.*[\W_]) simbol karakter (non-alphanumeric)
        if (preg_match($pattern, $password)) {
            $validateData['password'] = Hash::make($validateData['password']);
            User::create($validateData, $messages);
            return redirect()->route('backend.user.index')->with('success', 'Data Berhasil Disimpan');
        } else {
            return redirect()->back()->withErrors(['password' => 'password harus Terdiri dari kombinasi huruf besar dan kecil, angka, simbol karakter']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = user::findOrFail($id);
        return view('backend.v-user.edit', [
            'judul' => 'Edit Data User',
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request);
        $user = user::findOrFail($id);
        $rules = [
            'nama' => 'required|max:255',
            'role' => 'required',
            'status' => 'required',
            'hp' => 'required|min:10|max:13',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];
        $messages = [
            'foto.image' => 'Mohon Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ];
        if ($request->email != $user->email) {
            $rules['email'] = 'required|max:255|email|unique:user';
        }
        $validateData = $request->validate($rules, $messages);

        //mengunakan helper image
        if ($request->file('foto')) {
            //hapus foto lama
            if ($user->foto) {
                $oldimagepath = public_path('storage/img-user/') . $user->foto;
                if (file_exists($oldimagepath)) {
                    unlink($oldimagepath);
                }
            }
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';
            //simpan gambar dengan ukuran yang ditentukan
            ImageHelper::uploadAndRisize($file, $directory, $originalFileName, 385, 400);
            // isi null jika ingin otomatis ukurannya
            //simpan nama file asli didatabase
            $validateData['foto'] = $originalFileName;
        }

        $user->update($validateData);
        return redirect()->route('backend.user.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = user::findOrfail($id);
        if ($user->foto) {
            $oldimagepath = public_path('storage/img-user/') . $user->foto;
            if (file_exists($oldimagepath)) {
                unlink($oldimagepath);
            }
        }
        $user->delete();
        return redirect()->route('backend.user.index')->with('success', 'Data berhasil dihapus');
    }

    public function fromUser()
    {
        return view('backend.v-user.formLaporan', [
            'judul' => 'Buat Laporan User'
        ]);
    }

    public function cetakLaporanUser(Request $request)
    {
        // dd($request);
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ], [
            'tanggal_awal.required' => 'Tanggal Harus Di isi!',
            'tanggal_akhir.required' => 'Tanggal Harus Di isi!',
            'tanggal_akhir.after_or_equal' => 'Tanggal Akhir Harus Setelah atau sama dengan Tanggal Awal!',
        ]);

        $tanggalawal = Carbon::parse($request->input('tanggal_awal'))->startOfDay();
        $tanggalakhir = Carbon::parse($request->input('tanggal_akhir'))->endOfDay();
        $query = User::whereBetween('created_at', [$tanggalawal, $tanggalakhir])->orderBy('id', 'desc');
        $user = $query->get();
        // var_dump($user);
        // die;
        return view('backend.v-hasilLaporan.cetakUser', [
            'judul' => 'Laporan User',
            'tanggalAwal' => $tanggalawal,
            'tanggalAkhir' => $tanggalakhir,
            'cetak' => $user,
        ]);
    }
}
