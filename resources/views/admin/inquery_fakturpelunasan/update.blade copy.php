@extends('layouts.app')

@section('title', 'Inquery Faktur Pelunasan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="containers-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inquery Faktur Pelunasan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/faktur_pelunasan') }}">Inquery Faktur Pelunasan</a>
                        </li>
                        <li class="breadcrumb-item active">Perbarui</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="containers-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            @if (session('erorrss'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    {{ session('erorrss') }}
                </div>
            @endif

            @if (session('error_pelanggans') || session('error_pesanans'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @if (session('error_pelanggans'))
                        @foreach (session('error_pelanggans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                    @if (session('error_pesanans'))
                        @foreach (session('error_pesanans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                </div>
            @endif
            <form action="{{ url('admin/inquery_fakturpelunasan/' . $inquery->id) }}" method="POST"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Perbarui Faktur Pelunasan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <label style="font-size:14px" class="form-label" for="kode_tagihan">Kode invoice</label>
                        <div class="form-group d-flex">
                            <input class="form-control" hidden id="tagihan_ekspedisi_id" name="tagihan_ekspedisi_id"
                                type="text" placeholder=""
                                value="{{ old('tagihan_ekspedisi_id', $inquery->tagihan_ekspedisi_id) }}" readonly
                                style="margin-right: 10px; font-size:14px" />
                            <input class="form-control" id="kode_tagihan" name="kode_tagihan" type="text" placeholder=""
                                value="{{ old('kode_tagihan', $inquery->kode_tagihan) }}" readonly style="font-size:14px" />
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        {{-- <div class="row"> --}}
                        {{-- <div class="col-md-4"> --}}
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Pelanggan</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group" hidden>
                                    <label for="pelanggan_id">return Id</label>
                                    <input type="text" class="form-control" id="pelanggan_id" readonly
                                        name="pelanggan_id" placeholder=""
                                        value="{{ old('pelanggan_id', $inquery->pelanggan_id) }}">
                                </div>
                                <div class="form-group">
                                    <label style="font-size:14px" for="kode_pelanggan">Kode Pelanggan</label>
                                    <input style="font-size:14px" type="text" class="form-control" id="kode_pelanggan"
                                        readonly name="kode_pelanggan" placeholder=""
                                        value="{{ old('kode_pelanggan', $inquery->kode_pelanggan) }}">
                                </div>
                                <div class="form-group">
                                    <label style="font-size:14px" for="nama_pelanggan">Nama Pelanggan</label>
                                    <input style="font-size:14px" type="text" class="form-control" id="nama_pelanggan"
                                        readonly name="nama_pelanggan" placeholder=""
                                        value="{{ old('nama_pelanggan', $inquery->nama_pelanggan) }}">
                                </div>
                                <div class="form-group" hidden>
                                    <div class="form-group">
                                        <label style="font-size:14px" for="alamat_pelanggan">Alamat
                                            return</label>
                                        <input style="font-size:14px" type="text" class="form-control"
                                            id="alamat_pelanggan" readonly name="alamat_pelanggan" placeholder=""
                                            value="{{ old('alamat_pelanggan', $inquery->alamat_pelanggan) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label style="font-size:14px" for="telp_pelanggan">No. Telp</label>
                                        <input style="font-size:14px" type="text" class="form-control"
                                            id="telp_pelanggan" readonly name="telp_pelanggan" placeholder=""
                                            value="{{ old('telp_pelanggan', $inquery->telp_pelanggan) }}">
                                    </div>
                                </div>
                                <div class="form-check" style="color:white">
                                    <label class="form-check-label">
                                        .
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- <div id="forms-containers"></div> --}}

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Form Faktur <span>
                                    </span></h3>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addFaks()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="font-size:14px" class="text-center">No</th>
                                            <th style="font-size:14px">Kode Faktur</th>
                                            <th style="font-size:14px">Tanggal Ekspedisi</th>
                                            <th style="font-size:14px">Total</th>
                                            <th style="font-size:14px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-fakturekspedisi">
                                        @foreach ($details as $detail)
                                            <tr id="fakturekspedisi-{{ $loop->index }}">
                                                <td style="width: 70px; font-size:14px" class="text-center"
                                                    id="urutanfaktur">{{ $loop->index + 1 }}
                                                </td>
                                                <td hidden>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="detail_idfaks-0"
                                                            name="detail_idfaks[]" value="{{ $detail['id'] }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            id="faktur_ekspedisi-{{ $loop->index }}"
                                                            name="faktur_ekspedisi[]" {{ $detail['faktur_ekspedisi'] }}
                                                            value="{{ $detail['faktur_ekspedisi'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="text" class="form-control"
                                                            readonly id="kode_faktur-{{ $loop->index }}"
                                                            name="kode_faktur[]" {{ $detail['kode_faktur'] }}
                                                            value="{{ $detail['kode_faktur'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="text" class="form-control"
                                                            readonly id="tanggal_faktur-{{ $loop->index }}"
                                                            name="tanggal_faktur[]" {{ $detail['tanggal_faktur'] }}
                                                            value="{{ $detail['tanggal_faktur'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="text" class="form-control"
                                                            id="total-{{ $loop->index }}" readonly name="total[]"
                                                            {{ $detail['total'] }}
                                                            value="{{ number_format($detail['total'], 0, ',', '.') }}">

                                                    </div>
                                                </td>
                                                <td style="width: 100px">
                                                    <button style="margin-left:5px" type="button"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="removeFak({{ $loop->index }}, {{ $detail['id'] }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Potongan Return <span>
                                    </span></h3>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="font-size:14px" class="text-center">No</th>
                                            <th style="font-size:14px">Kode Faktur</th>
                                            <th style="font-size:14px">Kode Potongan</th>
                                            <th style="font-size:14px">Keterangan</th>
                                            <th style="font-size:14px">Nominal</th>
                                            <th style="font-size:14px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-potongan">
                                        @foreach ($detailsreturn as $detail)
                                            <tr id="potongan-{{ $loop->index }}">
                                                <td style="width: 70px; font-size:14px" class="text-center"
                                                    id="urutanpotongan">{{ $loop->index + 1 }}
                                                </td>
                                                <td hidden>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="detail_ids-0"
                                                            name="detail_ids[]" value="{{ $detail['id'] }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            id="nota_return_id-{{ $loop->index }}"
                                                            name="nota_return_id[]" {{ $detail['nota_return_id'] }}
                                                            value="{{ $detail['nota_return_id'] }}">
                                                    </div>
                                                </td>
                                                <td style="width:25%">
                                                    <div class="form-group">
                                                        <select class="form-control" id="faktur_id-{{ $loop->index }}"
                                                            name="faktur_id[]">
                                                            <option value="">- Pilih Faktur -</option>
                                                            @foreach ($fakturs as $faktur)
                                                                <option value="{{ $faktur->id }}"
                                                                    {{ old('faktur_id.' . $loop->parent->index, $detail['faktur_ekspedisi_id']) == $faktur->id ? 'selected' : '' }}>
                                                                    {{ $faktur->kode_faktur }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input onclick="potonganmemo({{ $loop->index }})"
                                                            style="font-size:14px" type="text" class="form-control"
                                                            readonly id="kode_potongan-{{ $loop->index }}"
                                                            name="kode_potongan[]" {{ $detail['kode_potongan'] }}
                                                            value="{{ $detail['kode_potongan'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input onclick="potonganmemo({{ $loop->index }})"
                                                            style="font-size:14px" type="text" class="form-control"
                                                            readonly id="keterangan_potongan-{{ $loop->index }}"
                                                            name="keterangan_potongan[]"
                                                            {{ $detail['keterangan_potongan'] }}
                                                            value="{{ $detail['keterangan_potongan'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input onclick="potonganmemo({{ $loop->index }})"
                                                            style="font-size:14px" type="text" class="form-control"
                                                            id="nominal_potongan-{{ $loop->index }}" readonly
                                                            name="nominal_potongan[]" {{ $detail['nominal_potongan'] }}
                                                            value="{{ number_format($detail['nominal_potongan'], 0, ',', '.') }}">

                                                    </div>
                                                </td>
                                                <td style="width: 100px">
                                                    <button style="margin-left:5px" type="button"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="removeBan({{ $loop->index }}, {{ $detail['id'] }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="potonganmemo({{ $loop->index }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Potongan Lain-lain <span>
                                    </span></h3>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addTambahan()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="font-size:14px" class="text-center">No</th>
                                            <th style="font-size:14px">Kode Potongan</th>
                                            <th style="font-size:14px">Keterangan</th>
                                            <th style="font-size:14px">Nominal</th>
                                            <th style="font-size:14px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-pembelianlain">
                                        @foreach ($detailspotongan as $detail)
                                            <tr id="pembelianlain-{{ $loop->index }}">
                                                <td style="width: 70px; font-size:14px" class="text-center"
                                                    id="urutanlain">{{ $loop->index + 1 }}
                                                </td>
                                                <td hidden>
                                                    <div class="form-group" hidden>
                                                        <input type="text" class="form-control" id="nomor_seri-0"
                                                            name="detail_idss[]" value="{{ $detail['id'] }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            id="potongan_penjualan_id-{{ $loop->index }}"
                                                            name="potongan_penjualan_id[]"
                                                            value="{{ $detail['potongan_penjualan_id'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input onclick="biayatambah({{ $loop->index }})"
                                                            style="font-size:14px" type="text" class="form-control"
                                                            readonly id="kode_potonganlain-{{ $loop->index }}"
                                                            name="kode_potonganlain[]"
                                                            value="{{ $detail['kode_potonganlain'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input onclick="biayatambah({{ $loop->index }})"
                                                            style="font-size:14px" type="text" class="form-control"
                                                            readonly id="keterangan_potonganlain-{{ $loop->index }}"
                                                            name="keterangan_potonganlain[]"
                                                            value="{{ $detail['keterangan_potonganlain'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input onclick="biayatambah({{ $loop->index }})"
                                                            style="font-size:14px" type="text" class="form-control"
                                                            id="nominallain-{{ $loop->index }}" readonly
                                                            name="nominallain[]"
                                                            value="{{ number_format($detail['nominallain'], 0, ',', '.') }}">

                                                    </div>
                                                </td>
                                                <td style="width: 100px">
                                                    <button style="margin-left:5px" type="button"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="removeTambahan({{ $loop->index }}, {{ $detail['id'] }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="biayatambah({{ $loop->index }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Rincian Pembayaran</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="panjang">Kategori Pembayaran</label>
                                            <select style="font-size: 14px" class="form-control" id="kategori"
                                                name="kategori">
                                                <option value="">- Pilih -</option>
                                                <option value="Bilyet Giro"
                                                    {{ old('kategori', $inquery->kategori) == 'Bilyet Giro' ? 'selected' : null }}>
                                                    Bilyet Giro BG / Cek</option>
                                                <option value="Transfer"
                                                    {{ old('kategori', $inquery->kategori) == 'Transfer' ? 'selected' : null }}>
                                                    Transfer</option>
                                                <option value="Tunai"
                                                    {{ old('kategori', $inquery->kategori) == 'Tunai' ? 'selected' : null }}>
                                                    Tunai</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 14px" id="bg" for="lebar">No.
                                                BG/Cek</label>
                                            <label style="font-size: 14px" id="trans" for="lebar">No.
                                                Transfer</label>
                                            <label style="font-size: 14px" id="tun" for="lebar">Tunai</label>
                                            <input style="font-size: 14px" type="text" class="form-control"
                                                id="nomor" name="nomor" placeholder="masukkan no"
                                                value="{{ old('nomor', $inquery->nomor) }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="tinggi">Tanggal</label>
                                            <div class="input-group date" id="reservationdatetime">
                                                <input style="font-size: 14px" type="date" id="tanggal"
                                                    name="tanggal_transfer" placeholder="d M Y sampai d M Y"
                                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                                    value="{{ old('tanggal_transfer', $inquery->tanggal_transfer) }}"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#reservationdatetime">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="tinggi">Nominal</label>
                                            <input style="font-size: 14px" type="text" class="form-control"
                                                id="nominal" placeholder="masukkan nominal" name="nominal"
                                                name="nominal"
                                                value="{{ old('nominal', number_format($inquery->nominal, 0, ',', '.')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="margin-left: 89px">
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="totalpenjualan">Sub Total</label>
                                            <input style="text-align: end; font-size: 14px" type="text"
                                                class="form-control" id="totalpembayaran" readonly name="totalpenjualan"
                                                placeholder=""
                                                value="{{ old('totalpenjualan', number_format($inquery->totalpenjualan, 2, ',', '.')) }}">

                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="tinggi">Potongan Return</label>
                                            <input style="text-align: end; font-size: 14px" type="text"
                                                class="form-control" id="ongkosBongkar" readonly name="dp"
                                                placeholder=""
                                                value="{{ old('dp', number_format($inquery->dp, 2, ',', '.')) }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="tinggi">Potongan Lain-lain</label>
                                            <input style="text-align: end; font-size: 14px" type="text"
                                                class="form-control" id="tambahan_pembayaran" readonly
                                                name="potonganselisih" placeholder=""
                                                value="{{ old('potonganselisih', number_format($inquery->potonganselisih, 2, ',', '.')) }}">
                                        </div>
                                        <hr style="border: 2px solid black;">
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="tinggi">Total Pembayaran</label>
                                            <input style="text-align: end; font-size: 14px" type="text"
                                                class="form-control" id="totalPembayarans" readonly
                                                name="totalpembayaran" placeholder=""
                                                value="{{ old('totalpembayaran', number_format($inquery->totalpembayaran, 2, ',', '.')) }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="saldo_masuk">Pembayaran</label>
                                            <input style="text-align: end; font-size: 14px" type="text"
                                                class="form-control" readonly id="saldo_masuk" name="saldo_masuk"
                                                placeholder=""
                                                value="{{ old('saldo_masuk', number_format($inquery->saldo_masuk, 2, ',', '.')) }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 14px" for="tinggi">Selisih Pembayaran</label>
                                            <input style="text-align: end; font-size: 14px" type="text"
                                                class="form-control" id="hasilselisih" readonly name="selisih"
                                                value="{{ old('selisih', number_format($inquery->selisih, 2, ',', '.')) }}"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                                <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                                <div id="loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>

        <div class="modal fade" id="tableReturn" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Pelanggan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Penerimaan</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    {{-- <th>No Kabin</th>
                                    <th>Sopir</th> --}}
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $return)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $return->kode_tagihan }}</td>
                                        <td>{{ $return->tanggal }}</td>
                                        <td>{{ $return->nama_pelanggan }}</td>
                                        {{-- <td>{{ $return->no_kabin }}</td>
                                        <td>{{ $return->nama_driver }}</td> --}}
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="GetReturn(
                                                '{{ $return->id }}',
                                                '{{ $return->kode_tagihan }}',
                                                '{{ $return->pelanggan_id }}',
                                                '{{ $return->kode_pelanggan }}',
                                                '{{ $return->nama_pelanggan }}',
                                                '{{ $return->telp_pelanggan }}',
                                                '{{ $return->alamat_pelanggan }}',
                                                '{{ $return->detail_tagihan->where('faktur_ekspedisi.status_pelunasan', null)->pluck('faktur_ekspedisi_id')->implode(', ') }}',
                                                '{{ $return->detail_tagihan->where('faktur_ekspedisi.status_pelunasan', null)->pluck('kode_faktur')->implode(', ') }}',
                                                '{{ $return->detail_tagihan->where('faktur_ekspedisi.status_pelunasan', null)->pluck('tanggal_memo')->implode(', ') }}',
                                                '{{ $return->detail_tagihan->where('faktur_ekspedisi.status_pelunasan', null)->pluck('faktur_ekspedisi.grand_total')->implode(', ') }}',
                                                '{{ $return->detail_tagihan->where('faktur_ekspedisi.status_pelunasan', null)->pluck('total_faktur')->implode(', ') }}'
                                                )">
                                                <i class="fas fa-plus"></i>
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tablePotongans" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Potongan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables6" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Potongan</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($returns as $potongan)
                                    <tr onclick="getPotongan({{ $loop->index }})" data-id="{{ $potongan->id }}"
                                        data-kode_penjualan="{{ $potongan->kode_penjualan }}"
                                        data-tanggal_awal="{{ $potongan->tanggal_awal }}"
                                        data-grand_total="{{ $potongan->grand_total }}"
                                        data-param="{{ $loop->index }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $potongan->kode_penjualan }}</td>
                                        <td>{{ $potongan->tanggal_awal }}</td>
                                        <td>{{ number_format($potongan->grand_total, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <button type="button" id="btnTambah" class="btn btn-primary btn-sm"
                                                onclick="getPotongan({{ $loop->index }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tableBiaya" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Potongan Lain-lain</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables66" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Biaya Tambahan</th>
                                    <th>Keterangan</th>
                                    <th>Nominal</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($potonganlains as $potonganlain)
                                    <tr onclick="getBiaya({{ $loop->index }})" data-id="{{ $potonganlain->id }}"
                                        data-kode_potongan="{{ $potonganlain->kode_potongan }}"
                                        data-keterangan="{{ $potonganlain->keterangan }}"
                                        data-grand_total="{{ $potonganlain->grand_total }}"
                                        data-param="{{ $loop->index }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $potonganlain->kode_potongan }}</td>
                                        <td>{{ $potonganlain->keterangan }}</td>
                                        <td>{{ number_format($potonganlain->grand_total, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <button type="button" id="btnTambah" class="btn btn-primary btn-sm"
                                                onclick="getBiaya({{ $loop->index }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        function updateGrandTotal() {
            var grandTotal = 0;
            // Loop through each row to sum up the total values
            $('.total').each(function() {
                grandTotal += parseFloat($(this).val().replace(/\D/g, '') ||
                    0); // Parse and sum up the total values
            });

            // Format grand total
            var grandTotalFormatted = formatRupiah(grandTotal);
            var grandlocale = grandTotal.toLocaleString('id-ID'); // Menggunakan toLocaleString() untuk format lokal

            // Update the displayed grand total
            $('#totalpembayaran').val(grandTotalFormatted);
            $('#grandTotalCell').text(grandlocale);

            // Optionally, update other related values or perform any necessary actions
        }

        function formatRupiah(number) {
            var formatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
            return formatted;
        }
    </script>


    <script>
        $(document).ready(function() {
            // Tambahkan event listener pada tombol "Simpan"
            $('#btnSimpan').click(function() {
                // Sembunyikan tombol "Simpan" dan "Reset", serta tampilkan elemen loading
                $(this).hide();
                $('#btnReset').hide(); // Tambahkan id "btnReset" pada tombol "Reset"
                $('#loading').show();

                // Lakukan pengiriman formulir
                $('form').submit();
            });
        });
    </script>

    <script>
        var data_pembelianfak = @json(session('data_pembelians1'));
        var jumlah_fak = 1;

        if (data_pembelianfak != null) {
            jumlah_fak = data_pembelianfak.length;
            $('#tabel-fakturekspedisi').empty();
            var urutanfak = 0;
            $.each(data_pembelianfak, function(key, value) {
                urutanfak = urutanfak + 1;
                itemPembelianfak(urutanfak, key, false, value);
            });
        }

        function updateUrutanFak() {
            var urutanfak = document.querySelectorAll('#urutanfaktur');
            for (let i = 0; i < urutanfak.length; i++) {
                urutanfak[i].innerText = i + 1;
            }
        }

        var counterfak = 0;

        function addFaks() {
            counterfak++;
            jumlah_fak = jumlah_fak + 1;

            if (jumlah_fak === 1) {
                $('#tabel-fakturekspedisi').empty();
            } else {
                // Find the last row and get its index to continue the numbering
                var lastRow = $('#tabel-fakturekspedisi tr:last');
                var lastRowIndex = lastRow.find('#urutanfaktur').text();
                jumlah_fak = parseInt(lastRowIndex) + 1;
            }

            console.log('Current jumlah_fak:', jumlah_fak);
            itemPembelianFak(jumlah_fak, jumlah_fak - 1);
            updateUrutanFak();
        }

        function removeFak(identifier, detailId) {
            var row = document.getElementById('fakturekspedisi-' + identifier);
            row.remove();

            $.ajax({
                url: "{{ url('admin/inquery_fakturpelunasan/deletedetailpelunasanreturn') }}/" + detailId,
                type: "POST",
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Data deleted successfully');
                },
                error: function(error) {
                    console.error('Failed to delete data:', error);
                }
            });
        }

        function itemPembelianFak(identifier, key, style, value = null) {
            var faktur_ekspedisi_id = '';
            var kode_faktur = '';
            var tanggal_faktur = '';
            var total = '';

            if (value !== null) {
                faktur_ekspedisi_id = value.faktur_ekspedisi_id;
                kode_faktur = value.kode_faktur;
                tanggal_faktur = value.tanggal_faktur;
                total = value.total;

            }

            // urutan 
            var item_fak = '<tr id="fakturekspedisi-' + key + '">';
            item_fak += '<td style="width: 70px; font-size:14px" class="text-center" id="urutanfaktur">' + key +
                '</td>';

            // faktur_ekspedisi_id 
            item_fak += '<td hidden>';
            item_fak += '<div class="form-group">'
            item_fak += '<input type="text" class="form-control" id="faktur_ekspedisi_id-' + key +
                '" name="faktur_ekspedisi_id[]" value="' + faktur_ekspedisi_id + '" ';
            item_fak += '</div>';
            item_fak += '</td>';

            // kode_faktur 
            item_fak += '<td>';
            item_fak += '<div class="form-group">'
            item_fak += '<input type="text" class="form-control" readonly style="font-size:14px" id="kode_faktur-' +
                key +
                '" name="kode_faktur[]" value="' + kode_faktur + '" ';
            item_fak += '</div>';
            item_fak += '</td>';

            // tanggal_faktur 
            item_fak += '<td>';
            item_fak += '<div class="form-group">'
            item_fak +=
                '<input type="text" class="form-control" readonly style="font-size:14px" id="tanggal_faktur-' +
                key +
                '" name="tanggal_faktur[]" value="' + tanggal_faktur + '" ';
            item_fak += '</div>';
            item_fak += '</td>';

            // total 
            item_fak += '<td>';
            item_fak += '<div class="form-group">'
            item_fak +=
                '<input type="text" class="form-control" style="font-size:14px" readonly id="total-' +
                key +
                '" name="total[]" value="' + total + '" ';
            item_fak += '</div>';
            item_fak += '</td>';

            item_fak += '<td style="width: 100px">';
            item_fak +=
                '<button type="button" style="margin-left:5px" class="btn btn-danger btn-sm" onclick="removeFak(' +
                key + ')">';
            item_fak += '<i class="fas fa-trash"></i>';
            item_fak += '</button>';
            item_fak += '</td>';
            item_fak += '</tr>';

            $('#tabel-fakturekspedisi').append(item_fak);
        }
    </script>

    <script>
        var data_pembelian = @json(session('data_pembelians2'));
        var jumlah_ban = 1;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-potongan').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, false, value);
            });
        }

        function updateUrutan() {
            var urutan = document.querySelectorAll('#urutanpotongan');
            for (let i = 0; i < urutan.length; i++) {
                urutan[i].innerText = i + 1;
            }
        }

        var counter = 0;

        function addPesanan() {
            counter++;
            jumlah_ban = jumlah_ban + 1;

            if (jumlah_ban === 1) {
                $('#tabel-potongan').empty();
            } else {
                // Find the last row and get its index to continue the numbering
                var lastRow = $('#tabel-potongan tr:last');
                var lastRowIndex = lastRow.find('#urutanpotongan').text();
                jumlah_ban = parseInt(lastRowIndex) + 1;
            }

            console.log('Current jumlah_ban:', jumlah_ban);
            itemPembelian(jumlah_ban, jumlah_ban - 1, true);
            updateUrutan();
        }

        function removeBan(identifier, detailId) {
            var row = document.getElementById('potongan-' + identifier);
            row.remove();

            $.ajax({
                url: "{{ url('admin/inquery_fakturpelunasan/deletedetailpelunasanreturn') }}/" + detailId,
                type: "POST",
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Data deleted successfully');
                },
                error: function(error) {
                    console.error('Failed to delete data:', error);
                }
            });
            updateGrandTotalreturn();
            updateSubTotalsxx();
            updateSubTotal();
        }

        function itemPembelian(identifier, key, style, value = null) {
            var nota_return_id = '';
            var faktur_id = '';
            var kode_potongan = '';
            var keterangan_potongan = '';
            var nominal_potongan = '';

            if (value !== null) {
                nota_return_id = value.nota_return_id;
                faktur_id = value.faktur_id;
                kode_potongan = value.kode_potongan;
                keterangan_potongan = value.keterangan_potongan;
                nominal_potongan = value.nominal_potongan;

            }

            // urutan 
            var item_pembelian = '<tr id="potongan-' + key + '">';
            item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutanpotongan">' + key +
                '</td>';

            // nota_return_id 
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" id="nota_return_id-' + key +
                '" name="nota_return_id[]" value="' + nota_return_id + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // kode_faktur 
            item_pembelian += '<td style="width:25%">';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<select class="form-control select2bs4" id="faktur_id-' + key +
                '" name="faktur_id[]">';
            item_pembelian += '<option value="">Pilih Faktur..</option>';
            item_pembelian += '@foreach ($fakturs as $faktur_id)';
            item_pembelian +=
                '<option value="{{ $faktur_id->id }}" {{ $faktur_id->id == ' + faktur_id + ' ? 'selected' : '' }}>{{ $faktur_id->kode_faktur }}</option>';
            item_pembelian += '@endforeach';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // kode_potongan 
            item_pembelian += '<td onclick="potonganmemo(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" readonly style="font-size:14px" id="kode_potongan-' +
                key +
                '" name="kode_potongan[]" value="' + kode_potongan + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // keterangan_potongan 
            item_pembelian += '<td onclick="potonganmemo(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input type="text" class="form-control" readonly style="font-size:14px" id="keterangan_potongan-' +
                key +
                '" name="keterangan_potongan[]" value="' + keterangan_potongan + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // nominal_potongan 
            item_pembelian += '<td onclick="potonganmemo(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input type="text" class="form-control" style="font-size:14px" readonly id="nominal_potongan-' +
                key +
                '" name="nominal_potongan[]" value="' + nominal_potongan + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            item_pembelian += '<td style="width: 100px">';
            item_pembelian +=
                '<button type="button" style="margin-left:5px" class="btn btn-danger btn-sm" onclick="removeBan(' +
                key + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian +=
                '<button style="margin-left:3px" type="button" class="btn btn-primary btn-sm" onclick="potonganmemo(' +
                key +
                ')">';
            item_pembelian += '<i class="fas fa-plus"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';

            if (style) {
                select2(key);
            }


            $('#tabel-potongan').append(item_pembelian);
            $('#faktur_id-' + key + '').val(faktur_id).attr('selected', true);

        }

        function select2(id) {
            $(function() {
                $('#faktur_id-' + id).select2({
                    theme: 'bootstrap4'
                });
            });
        }
    </script>

    <script>
        var activeSpecificationIndex = 0;

        function potonganmemo(param) {
            activeSpecificationIndex = param;
            // Show the modal and filter rows if necessary
            $('#tablePotongans').modal('show');
        }

        function getPotongan(rowIndex) {
            var selectedRow = $('#datatables6 tbody tr:eq(' + rowIndex + ')');
            var Potongan_id = selectedRow.data('id');
            var KodePotongan = selectedRow.data('kode_penjualan');
            var TanggalAwal = selectedRow.data('tanggal_awal');
            var GrandTotal = selectedRow.data('grand_total');

            // Update the form fields for the active specification
            $('#nota_return_id-' + activeSpecificationIndex).val(Potongan_id);
            $('#kode_potongan-' + activeSpecificationIndex).val(KodePotongan);
            $('#keterangan_potongan-' + activeSpecificationIndex).val(TanggalAwal);
            $('#nominal_potongan-' + activeSpecificationIndex).val(GrandTotal.toLocaleString('id-ID'));

            // var formattedNominal = parseFloat(Nominal).toLocaleString('id-ID');
            // document.getElementById('nominal_potongan-').value = formattedNominal;

            $('#tablePotongans').modal('hide');
            updateGrandTotalreturn();
            updateSubTotalsxx();
            updateSubTotal();
        }
    </script>

    <script>
        var data_pembelian = @json(session('data_pembelians3'));
        var jumlah_ban = 1;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelianlain').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelians(urutan, key, value);
            });
        }

        var counter = 0;

        function addTambahan() {
            counter++;
            jumlah_ban = jumlah_ban + 1;

            if (jumlah_ban === 1) {
                $('#tabel-pembelianlain').empty();
            } else {
                // Find the last row and get its index to continue the numbering
                var lastRow = $('#tabel-pembelianlain tr:last');
                var lastRowIndex = lastRow.find('#urutanlain').text();
                jumlah_ban = parseInt(lastRowIndex) + 1;
            }
            console.log('Current jumlah_ban:', jumlah_ban);
            itemPembelians(jumlah_ban, jumlah_ban - 1);
            updateUrutanss();
        }


        function updateUrutanss() {
            var urutan = document.querySelectorAll('#urutanlain');
            for (let i = 0; i < urutan.length; i++) {
                urutan[i].innerText = i + 1;
            }
        }

        function removeTambahan(identifier, detailId) {
            var row = document.getElementById('pembelianlain-' + identifier);
            row.remove();

            console.log(detailId);
            $.ajax({
                url: "{{ url('admin/inquery_fakturpelunasan/deletedetailpelunasanpotongan') }}/" + detailId,
                type: "POST",
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Data deleted successfully');
                },
                error: function(error) {
                    console.error('Failed to delete data:', error);
                }
            });

            updateGrandTotallain();
            updateSubTotalsxx();
            updateSubTotal();
        }

        function itemPembelians(identifier, key, value = null) {
            var potongan_penjualan_id = '';
            var kode_potonganlain = '';
            var keterangan_potonganlain = '';
            var nominallain = '';

            if (value !== null) {
                potongan_penjualan_id = value.potongan_penjualan_id;
                kode_potonganlain = value.kode_potonganlain;
                keterangan_potonganlain = value.keterangan_potonganlain;
                nominallain = value.nominallain;

            }

            // urutan 
            var item_pembelian = '<tr id="pembelianlain-' + key + '">';
            item_pembelian += '<td  style="width: 70px; font-size:14px" class="text-center" id="urutanlain">' + key +
                '</td>';

            // potongan_penjualan_id 
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" id="potongan_penjualan_id-' + key +
                '" name="potongan_penjualan_id[]" value="' + potongan_penjualan_id + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // kode_potonganlain 
            item_pembelian += '<td onclick="biayatambah(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input type="text" class="form-control" readonly style="font-size:14px" id="kode_potonganlain-' +
                key +
                '" name="kode_potonganlain[]" value="' + kode_potonganlain + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // keterangan_potonganlain 
            item_pembelian += '<td onclick="biayatambah(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input type="text" class="form-control" readonly style="font-size:14px" id="keterangan_potonganlain-' +
                key +
                '" name="keterangan_potonganlain[]" value="' + keterangan_potonganlain + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // nominallain 
            item_pembelian += '<td onclick="biayatambah(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input type="text" class="form-control" style="font-size:14px" readonly id="nominallain-' +
                key +
                '" name="nominallain[]" value="' + nominallain + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            item_pembelian += '<td style="width: 100px">';
            item_pembelian +=
                '<button type="button" style="margin-left:5px" class="btn btn-danger btn-sm" onclick="removeTambahan(' +
                key + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian +=
                '<button style="margin-left:3px" type="button" class="btn btn-primary btn-sm" onclick="biayatambah(' +
                key +
                ')">';
            item_pembelian += '<i class="fas fa-plus"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';

            $('#tabel-pembelianlain').append(item_pembelian);
        }
    </script>

    <script>
        var activeSpecificationIndex = 0;

        function biayatambah(param) {
            activeSpecificationIndex = param;
            // Show the modal and filter rows if necessary
            $('#tableBiaya').modal('show');
        }

        function getBiaya(rowIndex) {
            var selectedRow = $('#datatables66 tbody tr:eq(' + rowIndex + ')');
            var PotonganId = selectedRow.data('id');
            var KodeBiaya = selectedRow.data('kode_potongan');
            var NamabIaya = selectedRow.data('keterangan');
            var Nominal = selectedRow.data('grand_total');
            var kategori = $('#kategori').val(); // Get the value of the 'kategori' select element

            // Update the form fields for the active specification
            $('#potongan_penjualan_id-' + activeSpecificationIndex).val(PotonganId);
            $('#kode_potonganlain-' + activeSpecificationIndex).val(KodeBiaya);
            $('#keterangan_potonganlain-' + activeSpecificationIndex).val(NamabIaya);
            $('#nominallain-' + activeSpecificationIndex).val(Nominal.toLocaleString('id-ID'));

            // var formattedNominal = parseFloat(Nominal).toLocaleString('id-ID');
            // // Assuming 'biaya_tambahan' is an input element
            // document.getElementById('nominal').value = formattedNominal;
            // document.getElementById('harga_tambahan').value = formattedNominal;
            // document.getElementById('harga_tambahanborong').value = formattedNominal;

            $('#tableBiaya').modal('hide');

            updateGrandTotallain();
            updateSubTotalsxx();
            updateSubTotal();
        }
    </script>


    <script>
        function updateGrandTotalreturn() {
            var grandTotal = 0;

            // Loop through all elements with name "nominal_tambahan[]"
            $('input[name^="nominal_potongan"]').each(function() {
                var nominalValue = parseFloat($(this).val().replace(/\./g, '')) || 0; // Remove dots
                grandTotal += nominalValue;
            });

            $('#ongkosBongkar').val(formatRupiah(grandTotal));

        }

        $('body').on('input', 'input[name^="nominal_potongan"]', function() {
            updateGrandTotalreturn();
        });

        // Panggil fungsi saat halaman dimuat untuk menginisialisasi grand total
        $(document).ready(function() {
            updateGrandTotalreturn();
        });

        function formatRupiah(number) {
            var formatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
            return '' + formatted;
        }
    </script>

    <script>
        function updateGrandTotallain() {
            var grandTotal = 0;

            // Loop through all elements with name "nominal_tambahan[]"
            $('input[name^="nominallain"]').each(function() {
                var nominalValue = parseFloat($(this).val().replace(/\./g, '')) || 0; // Remove dots
                grandTotal += nominalValue;
            });

            $('#tambahan_pembayaran').val(formatRupiah(grandTotal));

        }

        $('body').on('input', 'input[name^="nominallain"]', function() {
            updateGrandTotallain();
        });

        // Panggil fungsi saat halaman dimuat untuk menginisialisasi grand total
        $(document).ready(function() {
            updateGrandTotallain();
        });

        function formatRupiah(number) {
            var formatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
            return '' + formatted;
        }
    </script>

    <script>
        function toggleLabels() {
            var kategori = document.getElementById('kategori');
            var bgLabel = document.getElementById('bg');
            var transLabel = document.getElementById('trans');
            var tunLabel = document.getElementById('tun');
            var Nomor = document.getElementById('nomor');

            if (kategori.value === 'Bilyet Giro') {
                bgLabel.style.display = 'block';
                transLabel.style.display = 'none';
                tunLabel.style.display = 'none';
                Nomor.style.display = 'block';
            } else if (kategori.value === 'Transfer') {
                bgLabel.style.display = 'none';
                transLabel.style.display = 'block';
                tunLabel.style.display = 'none';
                Nomor.style.display = 'block';
            } else if (kategori.value === 'Tunai') {
                bgLabel.style.display = 'none';
                transLabel.style.display = 'none';
                tunLabel.style.display = 'none';
                Nomor.style.display = 'none';
            }
        }

        toggleLabels();
        document.getElementById('kategori').addEventListener('change', toggleLabels);
    </script>

    <script>
        // fungsi total harga memo perjalanan 
        function updateSubTotalsxx() {
            var Uangjalans = $('#totalpembayaran').val().replace(/\./g, '').replace(',', '.') || '0';
            var HargaTambahan = $('#ongkosBongkar').val().replace(/\./g, '').replace(',', '.') || '0';
            var DepositDriv = $('#tambahan_pembayaran').val().replace(/\./g, '').replace(',', '.') || '0';

            Uangjalans = parseFloat(Uangjalans) || 0;

            console.log(Uangjalans);
            console.log(HargaTambahan);
            console.log(DepositDriv);
            // Menghitung sub total (1% dari UangJaminan)
            var Hasil = Uangjalans - HargaTambahan - DepositDriv;


            // Menetapkan nilai ke input uang_jaminan
            $('#totalPembayarans').val(formatRupiah(Hasil));

        }

        function parseCurrency(value) {
            return parseFloat(value.replace(/[^\d.-]/g, '')) || 0;
        }
    </script>

    <script>
        // Fungsi untuk menangani perubahan nilai pada input nominal
        $('#nominal').on('input', function() {
            // Mengambil nilai input nominal
            var nominalValue = $(this).val();

            // Memeriksa apakah input nominal kosong atau tidak
            if (nominalValue === "") {
                // Jika kosong, set form saldo masuk dan sub total menjadi 0
                $('#saldo_masuk').val("0,00");
                updateSubTotal(); // Memanggil fungsi updateSubTotal tanpa argumen
            } else {
                // Mengonversi nilai ke format rupiah
                var saldoMasukValue = formatRupiah(nominalValue);

                // Menetapkan nilai ke input saldo masuk
                $('#saldo_masuk').val(saldoMasukValue);

                // Memperbarui nilai sub total saat input nominal berubah
                updateSubTotal();
            }
        });

        function updateSubTotal() {
            // Mengambil nilai saldo masuk dan sisa saldo
            var saldoMasuk = parseCurrency($('#saldo_masuk').val());
            var sisaSaldo = parseCurrency($('#totalPembayarans').val());

            // Menghitung sub total
            var subTotal = sisaSaldo - saldoMasuk;

            // Mengonversi nilai sub total ke format rupiah
            var subTotalRupiah = subTotal.toLocaleString('id-ID', {
                minimumFractionDigits: 2
            });

            // Menetapkan nilai ke input sub total
            $('#hasilselisih').val("Rp " + subTotalRupiah);
        }

        // Fungsi untuk mengubah format uang ke angka
        function parseCurrency(value) {
            // Hilangkan semua karakter kecuali digit dan koma
            var cleanedValue = value.replace(/[^\d,]/g, '');
            // Ubah koma menjadi titik untuk memisahkan desimal
            cleanedValue = cleanedValue.replace(',', '.');
            // Ubah menjadi tipe data float
            return parseFloat(cleanedValue);
        }
    </script>


@endsection
