@extends('layouts.app')

@section('title', 'Inquery Pemasangan Ban')

@section('content')
    <!-- Content Header (Page header) -->
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("mainContent").style.display = "block";
                document.getElementById("mainContentSection").style.display = "block";
            }, 100); // Adjust the delay time as needed
        });
    </script>

    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inquery Pemasangan Ban</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Inquery Pemasangan Ban</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Inquery Pemasangan Ban</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <select class="custom-select form-control" id="status" name="status">
                                    <option value="">- Semua Status -</option>
                                    <option value="posting" {{ Request::get('status') == 'posting' ? 'selected' : '' }}>
                                        Posting
                                    </option>
                                    <option value="unpost" {{ Request::get('status') == 'unpost' ? 'selected' : '' }}>
                                        Unpost</option>
                                </select>
                                <label for="status">(Pilih Status)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_awal" name="tanggal_awal" type="date"
                                    value="{{ Request::get('tanggal_awal') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_awal">(Tanggal Awal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_awal">(Tanggal Akhir)</label>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="button" class="btn btn-outline-primary mr-2" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="card-body">
                        <table id="datatables66" class="table table-bordered table-striped table-hover"
                            style="font-size: 13px">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Pemasangan</th>
                                    <th>Tanggal</th>
                                    <th>No Kabin</th>
                                    <th>No Registrasi</th>
                                    <th>Jumlah Ban</th>
                                    <th>Jenis Kendaraan</th>
                                    <th class="text-center" width="30">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inquery as $pemasangan_ban)
                                    <tr id="editMemoekspedisi" data-toggle="modal"
                                        data-target="#modal-posting-{{ $pemasangan_ban->id }}" style="cursor: pointer;">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $pemasangan_ban->kode_pemasangan }}</td>
                                        <td>{{ $pemasangan_ban->tanggal_awal }}</td>
                                        <td>
                                            @if ($pemasangan_ban->kendaraan)
                                                {{ $pemasangan_ban->kendaraan->no_kabin }}
                                            @else
                                                Kabin tidak ada
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pemasangan_ban->kendaraan)
                                                {{ $pemasangan_ban->kendaraan->no_pol }}
                                            @else
                                                No pol tidak ada
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pemasangan_ban->kendaraan)
                                                {{ $pemasangan_ban->kendaraan->jenis_kendaraan->total_ban }}
                                            @else
                                                Ban tidak ada
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pemasangan_ban->kendaraan)
                                                {{ $pemasangan_ban->kendaraan->jenis_kendaraan->nama_jenis_kendaraan }}
                                            @else
                                                nama tidak ada
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($pemasangan_ban->status == 'posting')
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- <div class="modal fade" id="modal-hapus-{{ $pemasangan_ban->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Pemasangan</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Yakin hapus pemasangan ban
                                                        <strong>{{ $pemasangan_ban->kode_pembelian_ban }}</strong>?
                                                    </p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <form
                                                        action="{{ url('admin/inquery_pemasanganban/deleteban/' . $pemasangan_ban->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="modal fade" id="modal-posting-{{ $pemasangan_ban->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Opsi menu</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Pemasangan Ban
                                                        <strong>{{ $pemasangan_ban->kode_pemasangan }}</strong>
                                                    </p>
                                                    @if ($pemasangan_ban->status == 'unpost')
                                                        @if (auth()->check() && auth()->user()->fitur['inquery pemasangan ban delete'])
                                                            <form method="GET"
                                                                action="{{ route('hapuspemasanganban', ['id' => $pemasangan_ban->id]) }}">
                                                                <button type="submit"
                                                                    class="btn btn-outline-danger btn-block mt-2">
                                                                    <i class="fas fa-trash-alt"></i> Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if (auth()->check() && auth()->user()->fitur['inquery pemasangan ban show'])
                                                            <a href="{{ url('admin/lihat_pemasangan/' . $pemasangan_ban->id) }}"
                                                                type="button" class="btn btn-outline-info btn-block">
                                                                <i class="fas fa-eye"></i> Show
                                                            </a>
                                                        @endif
                                                        @if (auth()->check() && auth()->user()->fitur['inquery pemasangan ban update'])
                                                            <a href="{{ url('admin/inquery_pemasanganban/' . $pemasangan_ban->id . '/edit') }}"
                                                                type="button" class="btn btn-outline-warning btn-block">
                                                                <i class="fas fa-edit"></i> Update
                                                            </a>
                                                        @endif
                                                        @if (auth()->check() && auth()->user()->fitur['inquery pemasangan ban posting'])
                                                            <form method="GET"
                                                                action="{{ route('postingpemasangan', ['id' => $pemasangan_ban->id]) }}">
                                                                <button type="submit"
                                                                    class="btn btn-outline-success btn-block mt-2">
                                                                    <i class="fas fa-check"></i> Posting
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                    @if ($pemasangan_ban->status == 'posting')
                                                        @if (auth()->check() && auth()->user()->fitur['inquery pemasangan ban show'])
                                                            <a href="{{ url('admin/lihat_pemasangan/' . $pemasangan_ban->id) }}"
                                                                type="button" class="btn btn-outline-info btn-block">
                                                                <i class="fas fa-eye"></i> Show
                                                            </a>
                                                        @endif
                                                        @if (auth()->check() && auth()->user()->fitur['inquery pemasangan ban unpost'])
                                                            <form method="GET"
                                                                action="{{ route('unpostpemasangan', ['id' => $pemasangan_ban->id]) }}">
                                                                <button type="submit"
                                                                    class="btn btn-outline-primary btn-block mt-2">
                                                                    <i class="fas fa-check"></i> Unpost
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="modal fade" id="modal-unpost-{{ $pemasangan_ban->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">UNPOST</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Unpost pemasangan ban
                                                        <strong>{{ $pemasangan_ban->kode_pembelian_ban }}</strong>?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <a class="btn btn-primary"
                                                        href="{{ route('unpostpemasangan', ['id' => $pemasangan_ban->id]) }}">Ya</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modal-posting-{{ $pemasangan_ban->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">UNPOST</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Posting pemasangan ban
                                                        <strong>{{ $pemasangan_ban->kode_pembelian_ban }}</strong>?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <a class="btn btn-primary"
                                                        href="{{ route('postingpemasangan', ['id' => $pemasangan_ban->id]) }}">Ya</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('tanggal_awal');
        var tanggalAkhir = document.getElementById('tanggal_akhir');

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

        var form = document.getElementById('form-action');

        function cari() {
            form.action = "{{ url('admin/inquery_pemasanganban') }}";
            form.submit();
        }
    </script>
@endsection
