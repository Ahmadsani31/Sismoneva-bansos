@extends('layouts.app')
@section('content')
    <div class="container-fluid px-6 py-4">
        <div class="row">
            <x-breadcrumb title="Program" :links="[
                'Dashboard' => route('dashboard'),
                'Program' => '#',
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
                                <h3 class="mb-0">Data Program</h3>
                            </div>
                            <button class="btn btn-primary btn-sm modal-cre" id="program" parent="0"
                                judul="Tambah Bahan Baku"><i class="fa-solid fa-square-plus"></i> |
                                Tambah</button>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-center" id="DTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Tanggal </th>
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
        let DTable = new DataTable('#DTable', {
            ajax: "{{ route('datatable', ['tabel' => 'program']) }}",
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
                data: 'nama',
            }, {
                data: 'created_at',
            },{
                data: 'action',
            }, ],
        });
    </script>
@endPushOnce
