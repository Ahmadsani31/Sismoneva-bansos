@php
    $url_file = '';
    $file_type = '';
    $file_bukti = '';
    $query = \App\Models\Bantuan::find(request()->parent);

    if ($query) {
        $file_type = $query->file_type;
        $file_bukti = $query->file_bukti;
        $url_file = Illuminate\Support\Facades\Storage::url($file_bukti);
    }
@endphp
<div class="modal-body">
    @if ($file_type == 'pdf')
        <iframe src="{{ $url_file }}" width="100%" height="400"></iframe>
    @else
        <img src="{{ $url_file }}" class="img-fluid" alt="gambar">
    @endif
</div>
