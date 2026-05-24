<?php

namespace App\Http\Controllers\kepala_sekolah;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query();

        if ($request->filled('search')) {
            $query->where(fn($q) =>
                $q->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('nip', 'like', '%'.$request->search.'%')
            );
        }

        $guru          = $query->orderBy('nama')->paginate(20);
        $totalGuru     = Guru::count();
        $guruLaki      = Guru::where('jenis_kelamin', 'Laki-laki')->count();
        $guruPerempuan = Guru::where('jenis_kelamin', 'Perempuan')->count();

        return view('kepala_sekolah.guru', compact(
            'guru', 'totalGuru', 'guruLaki', 'guruPerempuan'
        ));
    }
}
