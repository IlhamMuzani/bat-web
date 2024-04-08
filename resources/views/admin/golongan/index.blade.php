@extends('layouts.app')

@section('title', 'Data Golongan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Golongan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Golongan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
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
                    <h3 class="card-title">Data Golongan</h3>
                    <div class="float-right">
                        @if (auth()->check() && auth()->user()->fitur['golongan create'])
                            <a href="{{ url('admin/golongan/create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead style="background-color: #000000; color: white;">
                            <tr>
                                <th style="text-align: center;">No</th>
                                <th>Kode Golongan</th>
                                <th>Nama Golongan</th>
                                <th style="text-align: center;" width="80">Qr Code</th>
                                <th style="text-align: center;" width="80">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($golongans as $golongan)
                                <tr style="background-color: #f8f9fa;">
                                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                                    <td>{{ $golongan->kode_golongan }}</td>
                                    <td>{{ $golongan->nama_golongan }}</td>
                                    <td style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML("$golongan->qrcode_golongan", 'QRCODE', 2, 2) !!}
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        @if (auth()->check() && auth()->user()->fitur['golongan update'])
                                            <a href="{{ url('admin/golongan/' . $golongan->id . '/edit') }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->fitur['golongan delete'])
                                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $golongan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection
