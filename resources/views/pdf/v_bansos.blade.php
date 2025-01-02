<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <h3 style="text-align: center">SISTEM MONITORING DAN EVALUASI PROGRAM BANTUAN SOSIAL</h3>
    <div style="margin-bottom: 5px;"></div>
    <table class="table table-bordered" border="1" style="text-align: center; width: 100%">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Program</th>
                <th>Jumlah</th>
                <th>Provinsi</th>
                <th width="100">Kabupaten</th>
                <th>Kecamatan</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($bantuan as $item)
                @php
                    $provinsi_id = DB::table('indonesia_provinces')
                        ->select('name')
                        ->where('id', $item->provinsi_id)
                        ->pluck('name');
                    $kabupaten_id = DB::table('indonesia_cities')
                        ->select('name')
                        ->where('id', $item->kabupaten_id)
                        ->pluck('name');
                    $kecamatan_id = DB::table('indonesia_districts')
                        ->select('name')
                        ->where('id', $item->kecamatan_id)
                        ->pluck('name');

                @endphp
                <tr>
                    <th width="30">
                        {{ $no++ }}
                    </th>
                    <td>{{ $item->program->nama }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $provinsi_id[0] }}</td>
                    <td width="100">{{ $kabupaten_id[0] }}</td>
                    <td>{{ $kecamatan_id[0] }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->tanggal }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>
