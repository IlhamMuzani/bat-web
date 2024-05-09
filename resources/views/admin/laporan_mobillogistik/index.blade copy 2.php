@extends('layouts.app')

@section('title', 'Laporan Mobil Logistik')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Mobil Logistik</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Laporan Mobil Logistik</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Laporan Mobil Logistik</h3>
                </div>

                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <!-- Pengaturan untuk input dan tombol -->
                            <div class="col-md-3 mb-3">
                                <label for="created_at">Tanggal Awal</label>
                                <input class="form-control" id="created_at" name="created_at" type="date"
                                    value="{{ Request::get('created_at') }}" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tanggal_akhir">Tanggal Akhir</label>
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="kendaraan_id">Kendaraan</label>
                                <select name="kendaraan_id" id="kendaraan_id" class="custom-select">
                                    <option value="">- Pilih -</option>
                                    @foreach ($kendaraans as $kendaraan)
                                        <option value="{{ $kendaraan->id }}"
                                            {{ Request::get('kendaraan_id') == $kendaraan->id ? 'selected' : '' }}>
                                            {{ $kendaraan->no_kabin }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <!-- Tombol untuk mencari dan mencetak -->
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReport()">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Perhitungan Nilai Total -->
                    @php
                        $totalGrandTotal = 0;
                        $totalMemo = 0;
                        $totalMemoTambahan = 0;

                        foreach ($inquery as $faktur) {
                            $totalGrandTotal += $faktur->grand_total; // Total Faktur

                            foreach ($faktur->detail_faktur as $memo) {
                                $totalMemo += $memo->memo_ekspedisi->hasil_jumlah ?? 0; // Total Memo Ekspedisi

                                if ($memo->memo_ekspedisi && $memo->memo_ekspedisi->memotambahan) {
                                    foreach ($memo->memo_ekspedisi->memotambahan as $memoTambahan) {
                                        $totalMemoTambahan += $memoTambahan->grand_total ?? 0; // Total Memo Tambahan
                                    }
                                }
                            }
                        }

                        // Hitung selisih antara total faktur dengan total memo + memo tambahan
                        $selisih = $totalGrandTotal - ($totalMemo + $totalMemoTambahan);
                    @endphp

                    <!-- Tampilkan Nilai Total -->
                    <div class="row mt-4"> <!-- Tambahkan margin-top -->
                        <div class="col-md-6">
                            <!-- Ruang kosong atau konten tambahan -->
                            <div class="card"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Total Faktur -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label style="font-size:14px;">Total Faktur:</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input style="text-align: end; font-size:14px;" type="text"
                                                class="form-control"
                                                value="{{ number_format($totalGrandTotal, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Total Memo -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label style="font-size:14px;">Total Memo:</label>
                                        </div>
                                        <div kol-md-6">
                                            <input style="text-align: end; font-size:14px;" type="text"
                                                class="form-control"
                                                value="{{ number_format($totalMemo + $totalMemoTambahan, 0, ',', '.') }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Divider -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <hr style="border: 2px solid black;">
                                        </div>
                                    </div>

                                    <!-- Selisih -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label style="font-size:14px;">Selisih:</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input style="text-align: end; font-size:14px;" type="text"
                                                class="form-control" value="{{ number_format($selisih, 0, ',', '.') }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('created_at');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
        var kendaraanId = document.getElementById('kendaraan_id');
        var form = document.getElementById('form-action');

        if (tanggalAwal.value == "") {
            tanggalAkhir.readOnly = true;
        }

        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
            }
            tanggalAkhir.value = "";
            var today = new Date().toISOString().split('T')[0];
            tanggalAkhir.value = today;
            tanggalAkhir.setAttribute('min', this.value);
        });

        function cari() {
            // Dapatkan nilai tanggal awal dan tanggal akhir
            var startDate = tanggalAwal.value;
            var endDate = tanggalAkhir.value;
            var Kendaraanid = kendaraanId.value;

            // Cek apakah tanggal awal dan tanggal akhir telah diisi
            if (startDate && endDate && Kendaraanid) {
                form.action = "{{ url('admin/laporan_mobillogistik') }}";
                form.submit();
            } else {
                alert("Silakan pilih kendaraan dan isi kedua tanggal sebelum mencetak.");
            }
        }


        function printReport() {
            var startDate = tanggalAwal.value;
            var endDate = tanggalAkhir.value;

            if (startDate && endDate) {
                form.action = "{{ url('admin/print_mobillogistik') }}" + "?start_date=" + startDate + "&end_date=" +
                    endDate;
                form.submit();
            } else {
                alert("Silakan isi kedua tanggal sebelum mencetak.");
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            // Detect the change event on the 'status' dropdown
            $('#statusx').on('change', function() {
                // Get the selected value
                var selectedValue = $(this).val();

                // Check the selected value and redirect accordingly
                switch (selectedValue) {
                    case 'laporandetail':
                        window.location.href = "{{ url('admin/laporan_mobillogistik') }}";
                        break;
                    case 'laporanglobal':
                        window.location.href = "{{ url('admin/laporan_mobillogistikglobal') }}";
                        break;
                        // case 'akun':
                        //     window.location.href = "{{ url('admin/laporan_pengeluarankaskecilakun') }}";
                        //     break;
                        // case 'memo_tambahan':
                        //     window.location.href = "{{ url('admin/laporan_saldokas') }}";
                        //     break;
                    default:
                        // Handle other cases or do nothing
                        break;
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var toggleAll = $("#toggle-all");
            var isExpanded = false; // Status untuk melacak apakah semua detail telah dibuka

            toggleAll.click(function() {
                if (isExpanded) {
                    $(".collapse").collapse("hide");
                    toggleAll.text("All Toggle Detail");
                    isExpanded = false;
                } else {
                    $(".collapse").collapse("show");
                    toggleAll.text("All Close Detail");
                    isExpanded = true;
                }
            });

            // Event listener untuk mengubah status jika ada interaksi manual
            $(".accordion-toggle").click(function() {
                var target = $(this).data("target");
                if ($("#" + target).hasClass("show")) {
                    $("#" + target).collapse("hide");
                } else {
                    $("#" + target).collapse("show");
                }
            });
        });
    </script>
@endsection
