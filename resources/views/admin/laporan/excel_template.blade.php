<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="border: 1px solid #ddd; padding: 8px;">No</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Nama Siswa</th>
            <th style="border: 1px solid #ddd; padding: 8px;">NIS</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Kelas</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Mata Pelajaran</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Nilai</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Status</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Feedback</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Tanggal Penilaian</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $row)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $row->nama_siswa }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $row->nis }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $row->kelas }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $row->mapel }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $row->nilai }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ ucfirst($row->status) }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $row->feedback }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $row->tanggal_nilai }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
