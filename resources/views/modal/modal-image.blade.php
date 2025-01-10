@php
    $url_file = '';
    $file_type = '';
    $query = \App\Models\Bantuan::find(request()->parent);

    if ($query) {
        $file_type = $query->file_type;
        $file_bukti = $query->file_bukti;
    }
@endphp
<div class="modal-body">
    @if ($file_type == 'pdf')
        <iframe src="{{ $file_bukti }}" width="100%" height="400"></iframe>
    @else
        <img src="{{ $file_bukti }}" class="img-fluid" alt="gambar">
    @endif
</div>
