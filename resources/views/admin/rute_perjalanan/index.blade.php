@extends('layouts.app')

@section('title', 'Rute Perjalan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Rute Perjalan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Rute Perjalan</li>
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
                    <h3 class="card-title">Rute Perjalan</h3>
                    <div class="float-right">
                        @if (auth()->check() && auth()->user()->fitur['rute create'])
                            <a href="{{ url('admin/rute_perjalanan/create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode</th>
                                <th>Rute Perjalanan</th>
                                @if ($golongan->count() >= 1)
                                    <th class="text-center">Golongan 1</th>
                                @endif
                                @if ($golongan->count() >= 2)
                                    <th class="text-center">Golongan 2</th>
                                @endif
                                @if ($golongan->count() >= 3)
                                    <th class="text-center">Golongan 3</th>
                                @endif
                                @if ($golongan->count() >= 4)
                                    <th class="text-center">Golongan 4</th>
                                @endif
                                @if ($golongan->count() >= 5)
                                    <th class="text-center">Golongan 5</th>
                                @endif
                                @if ($golongan->count() >= 6)
                                    <th class="text-center">Golongan 6</th>
                                @endif
                                @if ($golongan->count() >= 7)
                                    <th class="text-center">Golongan 7</th>
                                @endif
                                @if ($golongan->count() >= 8)
                                    <th class="text-center">Golongan 8</th>
                                @endif
                                @if ($golongan->count() >= 9)
                                    <th class="text-center">Golongan 9</th>
                                @endif
                                @if ($golongan->count() >= 10)
                                    <th class="text-center">Golongan 10</th>
                                @endif
                                <th class="text-center" width="90">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rute_perjalanans as $rute_perjalanan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $rute_perjalanan->kode_rute }}
                                    </td>
                                    <td>{{ $rute_perjalanan->nama_rute }}
                                    </td>
                                    @if ($golongan->count() >= 1)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan1, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 2)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan2, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 3)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan3, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 4)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan4, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 5)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan5, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 6)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan6, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 7)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan7, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 8)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan8, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 9)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan9, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($golongan->count() >= 10)
                                        <td class="text-right">{{ number_format($rute_perjalanan->golongan10, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        @if (auth()->check() && auth()->user()->fitur['rute update'])
                                            <a href="{{ url('admin/rute_perjalanan/' . $rute_perjalanan->id . '/edit') }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if (auth()->check() && auth()->user()->fitur['rute delete'])
                                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $rute_perjalanan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-hapus-{{ $rute_perjalanan->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus Rute Perjalanan</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin hapus rute perjalanan
                                                    <strong>{{ $rute_perjalanan->nama_type }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ url('admin/rute_perjalanan/' . $rute_perjalanan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
