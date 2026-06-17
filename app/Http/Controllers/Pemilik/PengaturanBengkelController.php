<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PengaturanBengkel;

class PengaturanBengkelController extends Controller
{
    /**
     * Display pengaturan bengkel.
     */
    public function index()
    {
        $pengaturan = DB::table('pengaturan_bengkel')
        ->get()
        ->keyBy('key');

        // Jika tabel kosong, kembalikan array kosong
        if ($pengaturan->isEmpty()) {
            $pengaturan = collect();
        }

        return view('pemilik.pengaturan-bengkel', [
            'pengaturan' => $pengaturan,
        ]);
    }

    /**
     * Update pengaturan bengkel.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_bengkel' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'jam_operasional' => 'required|string|max:100',
            'catatan_nota' => 'required|string',
        ], [
            'nama_bengkel.required' => 'Nama bengkel harus diisi',
            'alamat.required' => 'Alamat bengkel harus diisi',
            'no_telepon.required' => 'No telepon harus diisi',
            'jam_operasional.required' => 'Jam operasional harus diisi',
            'catatan_nota.required' => 'Catatan nota harus diisi',
        ]);

        foreach ($validated as $key => $value) {
            PengaturanBengkel::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        return back()->with('success', 'Pengaturan bengkel berhasil disimpan');
    }
}
