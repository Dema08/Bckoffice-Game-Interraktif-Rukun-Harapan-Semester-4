<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {

        return response()->json(MataPelajaran::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required',
            'deskripsi'  => 'required',
        ]);

        $mapel = MataPelajaran::create($request->all());
        return response()->json($mapel, 201);
    }

    public function show($id)
    {
        $mapel = MataPelajaran::find($id);
        if (!$mapel) return response()->json(['message' => 'Data tidak ditemukan'], 404);

        return response()->json($mapel);
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::find($id);
        if (!$mapel) return response()->json(['message' => 'Data tidak ditemukan'], 404);

        $mapel->update($request->all());
        return response()->json($mapel);
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::find($id);
        if (!$mapel) return response()->json(['message' => 'Data tidak ditemukan'], 404);

        $mapel->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
