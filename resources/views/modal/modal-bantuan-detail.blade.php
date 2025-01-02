@php
    $program_id = '';
    $jumlah = '';
    $provinsi_id = '';
    $kabupaten_id = '';
    $kecamatan_id = '';
    $tanggal = '';
    $keterangan = '';
    $query = \App\Models\Bantuan::find(request()->parent);
    if ($query) {
        $program_id = $query->program_id;
        $jumlah = $query->jumlah;
        $provinsi_id = $query->provinsi_id;
        $kabupaten_id = $query->kabupaten_id;
        $kecamatan_id = $query->kecamatan_id;
        $tanggal = $query->tanggal;
        $status = $query->status;
        $keterangan = $query->keterangan;
        $file_type = $query->file_type;
        $file_bukti = $query->file_bukti;
        $keterangan_status = $query->keterangan_status;

        $url_file = Illuminate\Support\Facades\Storage::url($file_bukti);

        $provinsi_id = DB::table('indonesia_provinces')->select('name')->where('id', $provinsi_id)->pluck('name');
        $kabupaten_id = DB::table('indonesia_cities')->select('name')->where('id', $kabupaten_id)->pluck('name');
        $kecamatan_id = DB::table('indonesia_districts')->select('name')->where('id', $kecamatan_id)->pluck('name');
    }

@endphp
<div class="modal-body">
    @if ($file_type == 'pdf')
        <iframe src="{{ $url_file }}" width="100%" height="400"></iframe>
    @else
        <div class="overflow-auto" style="height: 400px">
            <img src="{{ $url_file }}" class="img-fluid" alt="gambar">

        </div>
    @endif
    <hr>
    <table class="table text-center mt-2">

        <tr>
            <th>Program</th>
            <td>{{ $query->program->nama }}</td>

        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ $jumlah }} bantuan</td>
        </tr>
        <tr>
            <th>Wilayah</th>
            <td>{{ $provinsi_id[0] }} > {{ $kabupaten_id[0] }} > {{ $kecamatan_id[0] }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ Carbon\Carbon::create($tanggal)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Keterangan</th>
            <td>{{ $keterangan }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @php
                    if ($status == 'pending') {
                        echo '<span class="badge bg-warning text-white p-1">Pending</span>';
                    } elseif ($status == 'disetujui') {
                        echo '<span class="badge bg-success text-white p-1">Disetujui</span>';
                    } else {
                        echo '<span class="badge bg-danger text-white p-1">Ditolak</span>';
                    }
                @endphp
            </td>
        </tr>
    </table>
    @if ($status == 'ditolak')
        <div class="alert alert-warning text-center" role="alert">
            <h4 class="alert-heading">Keterangan Ditolak</h4>
            <p class="m-0">{{ $keterangan_status }}</p>
        </div>
    @endif

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
