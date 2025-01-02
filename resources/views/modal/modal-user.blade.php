<form action="{{ route('user.store') }}" onsubmit="return false" method="post" id="form-action">
    <input type="hidden" name="ID" value="{{ request()->parent }}">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <x-input-label for="name" :value="__('Nama')" />
            <x-input id="name" type="text" name="name" :value="old('nama')" autofocus
                placeholder="Tulis nama" />
        </div>
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-input id="email" type="email" name="email" :value="old('email')" placeholder="Tulis email" />
        </div>
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-input id="password" type="password" name="password" :value="old('password')" placeholder="Tulis password" />
        </div>
        <div class="mb-3">
            <x-input-label for="level" :value="__('Level')" />
            <select class="form-control select-2" name="level" id="level">
                {!! OptionCreate([1, 2], ['Admin', 'Operator'], '') !!}
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-control select-2" name="aktif" id="aktif">
                {!! OptionCreate(['Y', 'N'], ['Aktif', 'Non Aktif'], '') !!}
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </div>
</form>
<script>
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
