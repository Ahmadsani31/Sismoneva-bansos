@extends('layouts.app')
@section('content')
    <div class="container-fluid px-6 py-4">
        <div class="row">
            <x-breadcrumb title="{{ $pageTitle }}" :links="[
                'Dashboard' => route('dashboard'),
                $pageTitle => '#',
            ]" />
        </div>
        <!-- table -->
        <div class="row mb-6">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <!-- Card -->
                <div class="card">
                    <div class="card-header bg-white  py-4">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h3 class="mb-0">Data {{ $pageTitle }}</h3>
                            </div>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <button type="button" onclick="logOutSemua()" class="btn btn-warning"><i
                                        class="fa-solid fa-right-from-bracket me-2"></i>Log out semua</button>
                                <button type="button" class="btn btn-primary modal-cre" id="user" parent="0"><i
                                        class="fa-solid fa-square-plus me-2"></i>
                                    Tambah</button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-center" id="DTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@pushOnce('scripts')
    <script>
        function logOutSemua() {

            Swal.fire({
                title: "Perhatian!",
                text: "Kamu yakin ingin menghapus semua session user yang aktif (Log-out)?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    var base_url = $('meta[name="base-url"]').attr("content");
                    var page = base_url + "/user/logout-all";
                    console.log(page);
                    $('#page-pre-loader').show();
                    axios.get(page)
                        .then(function(response) {
                            $('#page-pre-loader').hide();
                            if (response.param == true) {
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
                            console.log(response);
                        })
                        .catch(function(error) {
                            $('#page-pre-loader').hide();
                            console.log(error);
                        });
                }
            });
        }

        function logOutUser($id) {
            Swal.fire({
                title: "Perhatian!",
                text: "Kamu yakin ingin menghapus session user ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    var base_url = $('meta[name="base-url"]').attr("content");
                    var page = base_url + "/user/logout/" + $id;
                    console.log(page);
                    $('#page-pre-loader').show();
                    axios.get(page)
                        .then(function(response) {
                            console.log(response);

                            $('#page-pre-loader').hide();
                            if (response.param == true) {
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
                        })
                        .catch(function(error) {
                            $('#page-pre-loader').hide();
                            console.log(error);
                        });
                }
            });
        }


        let DTable = new DataTable('#DTable', {
            ajax: "{{ route('datatable', ['tabel' => 'user']) }}",
            processing: true,
            columnDefs: [{
                className: "align-middle text-center",
                targets: ['_all'],
            }, {
                targets: 0,
                searchable: false,
                orderable: false,
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).html(row + 1 + '. '); // Updates the first column with index
                }
            }],
            order: [
                [1, 'asc']
            ],
            columns: [{
                data: null,
            }, {
                data: 'name',
            }, {
                data: 'email',
            }, {
                data: 'created_at',
            }, {
                data: 'aktif',
            }, {
                data: 'action',
            }, ],
        });
    </script>
@endPushOnce
