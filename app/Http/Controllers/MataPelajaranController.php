<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use App\Services\MataPelajaranService;

class MataPelajaranController extends Controller
{
    protected $service;

    public function __construct(MataPelajaranService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $mapel = $this->service->getAll();
        return view('admin.mata_pelajaran.index', compact('mapel'));
    }

    public function create()
    {
        return view('admin.mata_pelajaran.create');
    }

    public function store(Request $request)
    {
        $this->service->create($request->all());
        return redirect()->route('mata_pelajaran.index')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    public function edit(MataPelajaran $mata_pelajaran)
    {
        return view('admin.mata_pelajaran.edit', compact('mata_pelajaran'));
    }

    public function update(Request $request, MataPelajaran $mata_pelajaran)
    {
        $this->service->update($request->all(), $mata_pelajaran);
        return redirect()->route('mata_pelajaran.index')->with('success', 'Mata Pelajaran berhasil diperbarui!');
    }

    public function destroy(MataPelajaran $mata_pelajaran)
    {
        $this->service->delete($mata_pelajaran);
        return redirect()->route('mata_pelajaran.index')->with('success', 'Mata Pelajaran berhasil dihapus!');
    }
}
