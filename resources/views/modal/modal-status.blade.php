@php
    $program_id = '';
    $jumlah = '';
    $provinsi_id = '';
    $kabupaten_id = '';
    $kecamatan_id = '';
    $status = '';
    $keterangan_status = '';
    $query = \App\Models\Bantuan::find(request()->parent);
    if ($query) {
        $program_id = $query->program_id;
        $jumlah = $query->jumlah;
        $provinsi_id = $query->provinsi_id;
        $kabupaten_id = $query->kabupaten_id;
        $kecamatan_id = $query->kecamatan_id;
        $status = $query->status;
        $keterangan_status = $query->keterangan_status;
    }

@endphp
<form action="{{ route('bantuan.store-status') }}" onsubmit="return false" method="post" id="form-action">
    @csrf
    <input type="hidden" name="ID" value="{{ request()->parent }}">
    <div class="modal-body">
        <div class="mb-3">
            <select class="form-control select-2" name="status" id="value_status" onchange="changeStatus()" required
                style="width: 100%;">
                {!! OptionCreate(['pending', 'disetujui', 'ditolak'], ['Pending', 'Disetujui', 'Ditolak'], $status) !!}
            </select>
        </div>
        <div id="stt_keterangan"></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
<script>
    changeStatus()

    function changeStatus() {
        var sttValue = $('#value_status').val();
        if (sttValue == 'ditolak') {
            $('#stt_keterangan').html(`<x-form-textarea label="Keterangan" name="keterangan_status" placeholder="Tulis keterangan" rows="5"
            :value="$keterangan_status" required />`);
        } else {
            $('#stt_keterangan').html('');
        }

    }
    $('.select-2').select2({
        dropdownParent: $("#myModals")
    });


    $("form#form-action").on("submit", function(event) {
        event.preventDefault();
        $('#page-pre-loader').show();

        const form = this;
        let settings = {
            headers: {
                'content-type': 'multipart/form-data'
            }
        };
        axios.post($(form).attr('action'), form, settings)
            .then(response => {
                $('#page-pre-loader').hide();

                $('input').removeClass('is-invalid');
                $('textarea').removeClass('is-invalid');
                $('select').removeClass('is-invalid');
                if (response.data.param == true) {
                    $('#myModals').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.data.message,
                    });

                } else {
                    Toast.fire({
                        icon: 'warning',
                        title: response.data.message,
                    });
                }
                console.log(response)
            }).catch(error => {
                $('#page-pre-loader').hide();

                $('input').removeClass('is-invalid');
                $('textarea').removeClass('is-invalid');
                $('select').removeClass('is-invalid');
                if (error.response.status == 422) {
                    let msg = error.response.data.errors;
                    $.each(msg, function(key, value) {
                        console.log(key);
                        console.log(value);

                        $('#error-' + key).html(value[0]);
                        $('#' + key).addClass('is-invalid');
                    });

                    Swal.fire({
                        title: "Kesalahan",
                        text: error.response.data.message,
                        icon: "warning",

                    });
                } else {
                    Swal.fire({
                        title: "Kesalahan",
                        text: "error sistem",
                        icon: "error",
                        showConfirmButton: false,
                    });
                }

                console.log(error.response.data.message)
            }).finally(function() {
                DTable.ajax.reload(null, false);

            });
    });
</script>
