<!DOCTYPE html>
<html>
<head>
    <title>Laporan Nilai Ekstrakurikuler</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 2px 0; }
        .details { margin-bottom: 15px; }
        .details table { width: 100%; border: none; }
        .details td { padding: 2px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #000; padding: 5px; }
        table.data th { background-color: #f0f0f0; }
        .text-center { text-align: center; }
        .signature { margin-top: 40px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $unit->name ?? 'LPT NURUL ILMI' }}</h2>
        <h3>Laporan Nilai Ekstrakurikuler</h3>
    </div>

    <div class="details">
        <table>
            <tr>
                <td width="150">Ekstrakurikuler</td>
                <td>: {{ $extracurricular->name }}</td>
            </tr>
            <tr>
                <td>Tahun Pelajaran</td>
                <td>: {{ $academicYear->name }}</td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>: {{ ucfirst($semester) }}</td>
            </tr>
            <tr>
                <td>Pembina</td>
                <td>: {{ $extracurricular->coach_name ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Siswa</th>
                <th width="100">Kelas</th>
                <th width="50">Nilai</th>
                <th>Keterangan Capaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
                @php
                    $grade = $semester == 'ganjil' ? $member->grade_ganjil : $member->grade_genap;
                    $desc = $semester == 'ganjil' ? $member->description_ganjil : $member->description_genap;
                @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    {{ $member->student->nama_lengkap }}<br>
                    <small>NIS: {{ $member->student->nis }}</small>
                </td>
                <td class="text-center">{{ $member->student->schoolClass->first()->name ?? '-' }}</td>
                <td class="text-center">{{ $grade ?? '-' }}</td>
                <td>{{ $desc ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
