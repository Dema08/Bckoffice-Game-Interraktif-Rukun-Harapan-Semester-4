<?php
namespace App\Services;

use App\Models\MataPelajaran;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class MataPelajaranService
{
    public function getAll()
    {
        return MataPelajaran::all();
    }

    public function create(array $data)
    {
        $this->validate($data, [
            'kode_mapel' => 'required|unique:mata_pelajaran|max:20',
            'nama_mapel' => 'required',
            'deskripsi' => 'required',
        ]);

        return MataPelajaran::create($data);
    }

    public function update(array $data, MataPelajaran $mataPelajaran)
    {
        $this->validate($data, [
            'kode_mapel' => 'required|max:20|unique:mata_pelajaran,kode_mapel,' . $mataPelajaran->id,
            'nama_mapel' => 'required',
            'deskripsi' => 'required',
        ]);

        $mataPelajaran->update($data);
        return $mataPelajaran;
    }

    public function delete(MataPelajaran $mataPelajaran)
    {
        return $mataPelajaran->delete();
    }

    protected function validate(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}

