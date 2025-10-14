<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manggota;

class Canggota extends Controller
{
    public function index(Request $request)
    {
        // Query builder dengan filter
        $query = Manggota::query();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter berdasarkan pendidikan terakhir (TAMBAHAN BARU)
        if ($request->filled('pendidikan_terakhir')) {
            $query->where('pendidikan_terakhir', $request->pendidikan_terakhir);
        }

        // Filter berdasarkan tanggal daftar (dari - sampai)
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_daftar', [$request->dari, $request->sampai]);
        }

        // Filter berdasarkan pencarian nama
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('id_anggota', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('alamat', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Urutkan berdasarkan tanggal daftar terbaru
        $query->orderBy('tanggal_daftar', 'DESC');

        $data = $query->get();
        $nextIdAnggota = $this->generateIdAnggota();
        
        return view('anggota.index', compact('data', 'nextIdAnggota'));
    }

    private function generateIdAnggota()
    {
        $prefix = 'A-';
        $today = date('Ymd');
        
        $lastAnggota = Manggota::where('id_anggota', 'LIKE', $prefix . $today . '%')
                               ->orderBy('id_anggota', 'desc')
                               ->first();
        
        if ($lastAnggota) {
            $lastNumber = intval(substr($lastAnggota->id_anggota, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        
        return $prefix . $today . '-' . $newNumber;
    }

    public function save(Request $request)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('anggota.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk menambah data', 
                'icon' => 'error'
            ]);
        }

        $request->validate([
            'id_anggota' => 'required|unique:anggota,id_anggota',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'email' => 'nullable|email',
            'nomor_hp' => 'nullable|numeric'
        ]);

        $filename = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/foto'), $filename);
        }

        Manggota::create([
            'id_anggota' => $request->id_anggota,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'status' => $request->status ?? 'Aktif',
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'pekerjaan' => $request->pekerjaan,
            'instansi' => $request->instansi,
            'tanggal_daftar' => $request->tanggal_daftar,
            'berlaku_hingga' => $request->berlaku_hingga,
            'foto' => $filename
        ]);

        return redirect()->route('anggota.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data berhasil disimpan dengan ID: ' . $request->id_anggota, 
            'icon' => 'success'
        ]);
    }

    public function cetak(Request $request)
    {
        // Query dengan SEMUA filter untuk cetak
        $query = Manggota::query();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter pendidikan terakhir
        if ($request->filled('pendidikan_terakhir')) {
            $query->where('pendidikan_terakhir', $request->pendidikan_terakhir);
        }

        // Filter tanggal
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_daftar', [$request->dari, $request->sampai]);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('id_anggota', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('alamat', 'LIKE', '%' . $request->search . '%');
            });
        }

        $anggota = $query->orderBy('tanggal_daftar', 'DESC')->get();
        
        // Kirim info filter ke view
        $filterInfo = [
            'status' => $request->status,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'dari' => $request->dari,
            'sampai' => $request->sampai,
            'search' => $request->search,
        ];
        
        return view('anggota.cetak', compact('anggota', 'filterInfo'));
    }

    public function excel(Request $request)
    {
        header("Content-type: application/vnd-ms-excel");
        header('Content-Disposition: attachment;filename="data_anggota_' . date('Y-m-d_His') . '.xls"');
        header('Cache-Control: max-age=0');

        // Query dengan SEMUA filter untuk export
        $query = Manggota::query();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter pendidikan terakhir
        if ($request->filled('pendidikan_terakhir')) {
            $query->where('pendidikan_terakhir', $request->pendidikan_terakhir);
        }

        // Filter tanggal
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_daftar', [$request->dari, $request->sampai]);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('id_anggota', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('alamat', 'LIKE', '%' . $request->search . '%');
            });
        }

        $anggota = $query->orderBy('tanggal_daftar', 'DESC')->get();
        
        return view('anggota.excel', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('anggota.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk mengubah data', 
                'icon' => 'error'
            ]);
        }

        $anggota = Manggota::findOrFail($id);
        
        $request->validate([
            'id_anggota' => 'required|unique:anggota,id_anggota,' . $id,
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'email' => 'nullable|email',
            'nomor_hp' => 'nullable|numeric'
        ]);

        $filename = $anggota->foto;
        
        if ($request->hasFile('foto')) {
            if ($anggota->foto && file_exists(public_path('uploads/foto/' . $anggota->foto))) {
                unlink(public_path('uploads/foto/' . $anggota->foto));
            }
            
            $foto = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/foto'), $filename);
        }

        $anggota->update([
            'id_anggota' => $request->id_anggota,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'status' => $request->status ?? 'Aktif',
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'pekerjaan' => $request->pekerjaan,
            'instansi' => $request->instansi,
            'tanggal_daftar' => $request->tanggal_daftar,
            'berlaku_hingga' => $request->berlaku_hingga,
            'foto' => $filename
        ]);

       

        return redirect()->route('anggota.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data berhasil diupdate', 
            'icon' => 'success'
        ]);
    }

            public function cetakKartu($id)
            {
                $anggota = Manggota::findOrFail($id);
                return view('anggota.cetak_kartu', compact('anggota'));
            }

    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->level !== 'admin') {
            return redirect()->route('anggota.index')->with('status', [
                'judul' => 'Gagal', 
                'pesan' => 'Anda tidak memiliki akses untuk menghapus data', 
                'icon' => 'error'
            ]);
        }

        $anggota = Manggota::findOrFail($id);
        
        if ($anggota->foto && file_exists(public_path('uploads/foto/' . $anggota->foto))) {
            unlink(public_path('uploads/foto/' . $anggota->foto));
        }
        
        $anggota->delete();

        return redirect()->route('anggota.index')->with('status', [
            'judul' => 'Berhasil', 
            'pesan' => 'Data berhasil dihapus', 
            'icon' => 'success'
        ]);
    }
}