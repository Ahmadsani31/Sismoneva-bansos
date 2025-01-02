@extends('layouts.app')
@section('content')
    <div class="container-fluid px-6 py-4">
        <div class="row">
            <x-breadcrumb title="Bantuan" :links="[
                'Dashboard' => route('dashboard'),
                'Bantuan' => '#',
            ]" />
        </div>
        <!-- table -->
        <div class="row mb-6">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <!-- Card -->
                <div class="card">
                    <div class="card-header bg-white  py-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h3 class="mb-0">Data {{ $pageTitle }}</h3>
                            </div>
                            <button class="btn btn-primary btn-sm modal-cre" id="bantuan" parent="0"
                                judul="Tambah Bantuan"><i class="fa-solid fa-square-plus"></i> |
                                Tambah</button>
                        </div>
                        <form id="form-bansos" action="#" method="POST" target="_blank">
                            @csrf
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <select class="form-control select-2" name="program_id" id="program_id" required>
                                        <option value="">Pilih Program</option>
                                        {!! Option('program', 'id', '', 'nama') !!}
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select class="form-control select-2" name="provinsi_id" id="provinsi_id" required>
                                        <option value="">Pilih Provinsi</option>
                                        {!! OptionDaerahIndonesi('provinsi', '', '') !!}
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select class="form-control select-2" name="kabupaten_id" id="kabupaten_id" required>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select class="form-control select-2" name="kecamatan_id" id="kecamatan_id" required>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-grid gap-2">
                                        <button id="export_excel" class="btn btn-info" type="button"><i
                                                class="fa-regular fa-file-excel me-2"></i>Excel</button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-grid gap-2">
                                        <button id="export_pdf" class="btn btn-primary" type="button"><i
                                                class="fa-regular fa-file-pdf me-2"></i>PDF</button>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-center" id="DTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Program</th>
                                        <th>Jumlah</th>
                                        <th>Provinsi</th>
                                        <th>Status</th>
                                        <th>File</th>
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
        $('#program_id').on('change', function() {
            DTable.ajax.reload(null, false);
        });

        let DTable = new DataTable('#DTable', {
            ajax: {
                url: "{{ route('datatable', ['tabel' => 'bantuan']) }}",
                data: function(d) {
                    d.program_id = $('#program_id').val();
                    d.provinsi_id = $('#provinsi_id').val();
                    d.kabupaten_id = $('#kabupaten_id').val();
                    d.kecamatan_id = $('#kecamatan_id').val();
                }
            },
            processing: true,
            serverSide: true,
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
                [0, 'asc']
            ],
            columns: [{
                data: null,
            }, {
                data: 'program_id',
            }, {
                data: 'jumlah',
            }, {
                data: 'provinsi',
            }, {
                data: 'status',
            }, {
                data: 'file_bukti',
            }, {
                data: 'action',
            }, ],
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
            $('#provinsi_id').on('change', function() {
                onChangeSelect('{{ route('cities') }}', $(this).val(), 'kabupaten_id');
                $('#kecamatan_id').empty();
                DTable.ajax.reload(null, false);
            });
            $('#kabupaten_id').on('change', function() {
                onChangeSelect('{{ route('districts') }}', $(this).val(), 'kecamatan_id');
                DTable.ajax.reload(null, false);
            })
            $('#kecamatan_id').on('change', function() {
                onChangeSelect('{{ route('villages') }}', $(this).val(), '');
                DTable.ajax.reload(null, false);
            })
        });

        $('#export_excel').on('click', function() {

            $('form#form-bansos').attr('action', "{{ route('bantuan.excel') }}");
            $('form#form-bansos').submit();
        });
        $('#export_pdf').on('click', function() {

            $('form#form-bansos').attr('action', "{{ route('bantuan.pdf') }}");
            $('form#form-bansos').submit();
        });
    </script>
@endPushOnce
