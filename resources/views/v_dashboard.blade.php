@extends('layouts.app')
@section('content')
    <div class="pt-10 pb-18"></div>

    <div class="container-fluid mt-n22 px-6">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="mb-0 ">Dashboard</h3>
                </div>
            </div>
        </div>
        <div class="bg-info rounded-3">
            <div class="row mb-5 ">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="p-6 d-lg-flex justify-content-between align-items-center ">
                        <div class="d-md-flex align-items-center">
                            <div class="ms-md-4 mt-3 mt-md-0 lh-1">
                                <h3 class="text-white mb-0">Selamat Datang, {{ Auth::user()->name }}</h3>
                                <small class="text-white">Website Sistem Monitoring dan Evaluasi Program Bantuan
                                    Sosial</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-9 col-lg-6 col-md-12 col-12 mt-6">
                <!-- card -->
                <div class="card ">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="d-flex align-items-center  justify-content-between">
                            <div>
                                <h4 class="mb-0">Jumlah Bantuan Per-program</h4>
                            </div>

                        </div>
                        <div id="chartTabung"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12 col-12 mt-6">
                <!-- card -->
                <div class="card ">
                    <!-- card body -->
                    <div class="card-body">
                        <!-- heading -->
                        <div class="d-flex justify-content-between align-items-center
                    mb-3">
                            <div>
                                <h4 class="mb-0">Total Bantuan</h4>
                            </div>
                            <div class="icon-shape icon-md bg-light-primary text-primary
                      rounded-2">
                                <i class="bi bi-briefcase fs-4"></i>
                            </div>
                        </div>
                        <!-- project number -->
                        <div>
                            <h1 class="fw-bold">{{ $bantuan }}</h1>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- row  -->
        <div class="row my-6">
            <div class="col-lg-12 col-md-12 col-12 mb-6 mb-2">
                <!-- card  -->
                <div class="card">
                    <div class="card-header">
                        <select class="form-control select-2" name="aktif" id="aktif">
                            {!! OptionCreate(['provinsi', 'kabupaten'], ['Provinsi', 'Kabupaten'], '') !!}
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center  justify-content-between">
                            <div>
                                <h4 class="mb-0">Jumlah Bantuan Per-wilayah</h4>
                            </div>
                        </div>
                        <!-- chart  -->
                        <div class="mb-8">
                            <div id="bantuanPerwilayah"></div>
                        </div>
                        <!-- icon with content  -->

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@pushOnce('scripts')
    <script>
        var options = {
            series: [],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: [],
            },
            yaxis: {
                title: {
                    text: 'Jumlah'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " Bantuan"
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartTabung"), options);
        chart.render();


        var options1 = {
            series: [],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: [],
            },
            yaxis: {
                title: {
                    text: 'Jumlah'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " Bantuan"
                    }
                }
            }
        };

        var chart1 = new ApexCharts(document.querySelector("#bantuanPerwilayah"), options1);
        chart1.render();

        getProgram()
        async function getProgram() {
            try {
                var response = await axios.get("{{ route('bantuan.program') }}");
                console.log(response);
                var dSeries = response.data.items.series;
                var dCategories = response.data.items.categories;
                chart.updateOptions({
                    series: dSeries,
                    xaxis: {
                        categories: dCategories,
                    }
                })
            } catch (error) {
                console.error(error);
            }
        }
        getWilayah()
        async function getWilayah() {
            try {
                var response = await axios.get("{{ route('bantuan.wilayah') }}");
                console.log(response);
                var dSeries = response.data.items.series;
                var dCategories = response.data.items.categories;
                chart1.updateOptions({
                    series: dSeries,
                    xaxis: {
                        categories: dCategories,
                    }
                })
            } catch (error) {
                console.error(error);
            }
        }
    </script>
@endPushOnce
