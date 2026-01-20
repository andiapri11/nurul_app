<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; font-size: 16px; text-align: center;">LAPORAN PELANGGARAN SISWA</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center;">{{ $filterSummary['unit'] }} - Tahun Ajaran {{ $filterSummary['academic_year'] }}</th>
        </tr>
        <tr>
            <th colspan="7"></th>
        </tr>
        <tr style="background-color: #eeeeee; font-weight: bold;">
            <th style="border: 1px solid #000000; width: 5px;">No</th>
            <th style="border: 1px solid #000000; width: 15px;">Tanggal</th>
            <th style="border: 1px solid #000000; width: 25px;">Nama Siswa</th>
            <th style="border: 1px solid #000000; width: 10px;">Kelas</th>
            <th style="border: 1px solid #000000; width: 15px;">Jenis</th>
            <th style="border: 1px solid #000000; width: 40px;">Deskripsi Pelanggaran</th>
            <th style="border: 1px solid #000000; width: 10px;">Poin</th>
            <th style="border: 1px solid #000000; width: 20px;">Tindak Lanjut</th>
            <th style="border: 1px solid #000000; width: 15px;">Status</th>
            <th style="border: 1px solid #000000; width: 20px;">Pelapor</th>
        </tr>
    </thead>
    <tbody>
        @foreach($violations as $violation)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000000;">{{ \Carbon\Carbon::parse($violation->date)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #000000;">{{ $violation->student->nama_lengkap ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $violation->student->schoolClass->name ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ ucfirst($violation->violation_type) }}</td>
                <td style="border: 1px solid #000000;">{{ $violation->description }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $violation->points }}</td>
                <td style="border: 1px solid #000000;">{{ $violation->follow_up_notes ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">
                    @if($violation->follow_up_status == 'pending') Menunggu
                    @elseif($violation->follow_up_status == 'process') Proses
                    @elseif($violation->follow_up_status == 'done') Selesai
                    @endif
                </td>
                <td style="border: 1px solid #000000;">{{ $violation->recorder->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
