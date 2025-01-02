@php
    $program_id = '';
    $jumlah = '';
    $provinsi_id = '';
    $kabupaten_id = '';
    $kecamatan_id = '';
    $tanggal = '';
    $keterangan = '';
    $file_bukti = '';
    $text = 'text-danger';
    $url_file = 'javascript:void(0)';
    $query = \App\Models\Bantuan::find(request()->parent);
    if ($query) {
        $program_id = $query->program_id;
        $jumlah = $query->jumlah;
        $provinsi_id = $query->provinsi_id;
        $kabupaten_id = $query->kabupaten_id;
        $kecamatan_id = $query->kecamatan_id;
        $tanggal = $query->tanggal;
        $keterangan = $query->keterangan;
        $file_bukti = $query->file_bukti;
        if ($file_bukti) {
            $text = 'text-info';
            $url_file = route('bantuan.file', ['id' => encrypt($query->id)]);
        }
    }

@endphp
<form action="{{ route('bantuan') }}" onsubmit="return false" method="post" id="form-action">
    <input type="hidden" name="ID" value="{{ request()->parent }}">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <x-input-label for="program_id" :value="__('Program')" req="true" />
                    <select class="form-control select-2" name="program_id" id="program_id" required>
                        <option value="">Pilih Program</option>
                        {!! Option('program', 'id', $program_id, 'nama') !!}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <x-form-input-group label="Jumlah" name="jumlah" type="number" prepend="Qty" :value="$jumlah"
                    placeholder="Tulis Jumlah Bansos" required />
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <x-input-label for="provinsi" :value="__('Provinsi')" req="true" />
                    <select class="form-control select-2" name="provinsi_id" id="provinsi" required>
                        <option value="">Pilih Provinsi</option>
                        {!! OptionDaerahIndonesi('provinsi', '', $provinsi_id) !!}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <x-input-label for="kabupaten" :value="__('Kabupaten')" req="true" />
                    <select class="form-control select-2" name="kabupaten_id" id="kabupaten" required>
                        {!! OptionDaerahIndonesi('kabupaten', $provinsi_id, $kabupaten_id) !!}
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-input-label for="kecamatan" :value="__('Kecamatan')" req="true" />
            <select class="form-control select-2" name="kecamatan_id" id="kecamatan" required>
                {!! OptionDaerahIndonesi('kecamatan', $kabupaten_id, $kecamatan_id) !!}
            </select>
        </div>


        <x-form-input label="Tanggal" type="date" name="tanggal" :value="$tanggal" required />

        <div class="mb-3">
            <x-input-label for="file_bukti" :value="__('File Bukti')" req="true" />
            <div class="input-group">
                <input type="file" class="form-control" name="file_bukti" id="file_bukti"
                    accept="image/*,application/pdf">
                <span class="input-group-text">
                    <a href="{{ $url_file }}" target="_blank" class="{{ $text }}">
                        <i class="fa-solid fa-file-circle-exclamation fa-xl"></i>
                    </a>
                </span>
            </div>
        </div>
        <x-form-textarea label="Keterangan" name="keterangan" placeholder="Tulis keterangan (opsional)" rows="5"
            :value="$keterangan" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
<script>
    $('.select-2').select2({
        dropdownParent: $("#myModals")
    });

    function onChangeSelect(url, id, name) {
        $('#page-pre-loader').show();
        // send ajax request to get the cities of the selected province and append to the select tag
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                id: id
            },
            success: function(data) {
                $('#page-pre-loader').hide();
                $('#' + name).empty();
                $('#' + name).append('<option>Pilih Daerah</option>');

                $.each(data, function(key, value) {
                    $('#' + name).append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    }
    $(function() {
        $('#provinsi').on('change', function() {
            onChangeSelect('{{ route('cities') }}', $(this).val(), 'kabupaten');
            $('#kecamatan').empty();
        });
        $('#kabupaten').on('change', function() {
            onChangeSelect('{{ route('districts') }}', $(this).val(), 'kecamatan');
        })
        $('#kecamatan').on('change', function() {
            onChangeSelect('{{ route('villages') }}', $(this).val(), 'kelurahan');
        })
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
                DTable.ajax.reload(null, false);
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
                DTable.ajax.reload(null, false);

                if (error.response.status == 422) {
                    let msg = error.response.data.errors;
                    $.each(msg, function(key, value) {
                        console.log(key);
                        console.log(value);

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
            })
    });
</script>
