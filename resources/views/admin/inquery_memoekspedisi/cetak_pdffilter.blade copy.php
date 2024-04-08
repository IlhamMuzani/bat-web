<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Memo Ekspedisi</title>
    <style>
    html,
    body {
        font-family: 'DOSVGA', monospace;
        color: black;
        margin: 35px;
        margin-right: 80px;
    }

    .container {
        display: flex;
        justify-content: space-between;
        margin-top: 7rem;
    }

    .blue-button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        text-decoration: none;
        top: 50%;
        border-radius: 5px;
        transform: translateY(-50%);
    }

    .info-column {
        padding-left: 5px;
    }

    .info-titik {
        vertical-align: top;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .separator {
        padding-top: 14px;
        text-align: center;
    }

    .separator span {
        display: inline-block;
        border-top: 1px solid black;
        width: 100%;
        position: relative;
        top: -8px;
    }
    </style>
</head>
{{-- @foreach ($memos as $cetakpdf) --}}

<body style="margin: 0; padding: 0;">
    @for ($i = 0; $i < count($memos); $i +=2) @for ($j=$i; $j < $i + 2 && $j < count($memos); $j++) @php
        $cetakpdf=$memos[$j]; @endphp <table width="100%">
        <tr>
            <td style="width:0%;">
            </td>
            <td style="width: 70%; text-align: right;">
            </td>
        </tr>
        </table>
        <div style="text-align: center;">
            <span style="font-weight: bold; font-size: 25px;">PT BINA ANUGERAH TRANSINDO</span>
            <br>
            <span style=" font-size: 14px;">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL
            </span>
            <br>
            <span style=" font-size: 14px;">Telp / Fax, 02836195328 02838195187</span>
        </div>
        <hr style="border: 0.5px solid;">


        @if ($cetakpdf->kategori == 'Memo Perjalanan')
        <div style="text-align: center;">
            <span style="font-weight: bold; font-size: 20px;">MEMO PERJALANAN</span>
        </div>
        <table width="100%">
            <tr>
                <td style="width:60%;">
                    <table>
                        <tr>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">Tanggal</span>
                            </td>
                            <td class="info-column">
                                <span class="info-titik" style="font-size: 14px;">:</span>
                            </td>
                            <td class="info-column">
                                <span class="info-item"
                                    style="font-size: 14px;">{{ \Carbon\Carbon::parse($cetakpdf->tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">No. Memo</span>
                            </td>
                            <td class="info-column">
                                <span class="info-titik" style="font-size: 14px;">:</span>
                            </td>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">{{ $cetakpdf->kode_memo }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 70%; text-align: left;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 40%;">
                                <span class="info-item"
                                    style="font-size: 14px; text-align: left; display: inline-block;">No.
                                    Kabin</span>
                            </td>
                            <td style="width: 60%;">
                                <span class="info-item"
                                    style="font-size: 14px; text-align: left; display: inline-block;">:
                                    {{ $cetakpdf->no_kabin }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%;">
                                <span class="info-item"
                                    style="font-size: 14px; text-align: left; display: inline-block;">Km
                                    Awal</span>
                            </td>
                            <td style="width: 60%;">
                                <span class="info-item"
                                    style="font-size: 14px; text-align: left; display: inline-block;">:
                                    {{ $cetakpdf->km_awal }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr style="border: 0.5px solid;">

        <table width="100%" style="border-collapse: collapse;">
            <tr>
                <td style="width:100%;">
                    <table>
                        <tr>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">Kode Sopir</span>
                            </td>
                            <td class="info-column">
                                <span class="info-titik" style="font-size: 14px;">:</span>
                            </td>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">{{ $cetakpdf->kode_driver }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">Nama Sopir</span>
                            </td>
                            <td class="info-column">
                                <span class="info-titik" style="font-size: 14px;">:</span>
                            </td>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">{{ $cetakpdf->nama_driver }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">Rute Perjalanan</span>
                            </td>
                            <td class="info-column">
                                <span class="info-titik" style="font-size: 14px;">:</span>
                            </td>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">
                                    {{ $cetakpdf->nama_rute }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">Saldo Deposit</span>
                            </td>
                            <td class="info-column">
                                <span class="info-titik" style="font-size: 14px;">:</span>
                            </td>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px;">
                                    {{ number_format($cetakpdf->saldo_deposit, 2, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px; color:white">.</span>
                            </td>
                            <td class="info-column">
                                <span class="info-titik" style="font-size: 14px; color:white">.</span>
                            </td>
                            <td class="info-column">
                                <span class="info-item" style="font-size: 14px; color:white">.</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 70%;">
                    <table style="width: 100%;" cellpadding="2" cellspacing="0">
                        <tr>
                            <td colspan="5" style="text-align: left; padding-left: 0px; font-size: 14px;width: 25%;">
                                Uang
                                Jalan</td>
                            <td class="td" style="text-align: right; padding-right: 17px;; font-size: 14px;">
                                {{ number_format($cetakpdf->uang_jalan, 2, ',', '.') }}

                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: left; padding-left: 0px; font-size: 14px;">
                                Biaya
                                Tambahan
                            </td>
                            <td class="td" style="text-align: right; padding: 2px; font-size: 14px;">
                                {{ number_format($cetakpdf->biaya_tambahan, 2, ',', '.') }} -
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: left; padding-left: 0px; font-size: 14px;">
                                Potongan
                                Memo
                            </td>
                            <td class="td" style="text-align: right; padding: 2px; font-size: 14px;">
                                {{ number_format($cetakpdf->potongan_memo, 2, ',', '.') }} +
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" style="padding: 0px;">
                                <hr style="border-top: 0.5px solid black; margin: 5px 0;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: left; padding-left: 0px; font-size: 14px;">
                                jumlah
                            </td>
                            <td class="td" style="text-align: right; padding-right: 17px; font-size: 14px;">
                                {{ number_format($cetakpdf->uang_jalan + $cetakpdf->biaya_tambahan - $cetakpdf->potongan_memo, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: left; padding-left: 0px; font-size: 14px;">
                                Adm
                            </td>
                            <td class="td" style="text-align: right; padding-right: 17px; font-size: 14px;">
                                {{ number_format($cetakpdf->uang_jaminan, 2, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: left; padding-left: 0px; font-size: 14px;">
                                Deposit Sopir
                            </td>
                            <td class="td" style="text-align: right; padding: 0px; font-size: 14px;">
                                {{ number_format($cetakpdf->deposit_driver, 2, ',', '.') }} -
                            </td>
                        </tr>
                    </table>
                </td>

            </tr>
        </table>
        <hr style="border: 0.5px solid;">
        <table style="width: 100%;" cellpadding="2" cellspacing="0">
            <tr>
                <td class="td"
                    style="text-align: left; padding: 0px; font-size: 14px; white-space: nowrap; width: 50%;">
                    Ket Memo
                </td>
                <td class="td" style="text-align: center; padding-right: 10px; font-size: 14px; width: 25%;">
                    Grand Total
                </td>
                <td class="td"
                    style="text-align: right; padding-right: 17px; font-size: 14px; width: 20%; max-width: 100px; overflow: hidden; text-overflow: ellipsis;">
                    {{ number_format($cetakpdf->sub_total, 2, ',', '.') }}
                </td>
            </tr>
        </table>

        <div style="height: 48px; overflow: hidden;">
            <table style="width: 100%;" cellpadding="2" cellspacing="0">
                <tr>
                    <td class="td"
                        style="text-align: left; padding-right: 330px; font-size: 14px; white-space: normal;">
                        {{ $cetakpdf->keterangan }}
                    </td>
                </tr>
            </table>
        </div>
        @endif
        <br>
        <table class="tdd" cellpadding="10" cellspacing="0" style="margin: 0 auto;">
            <tr>
                <td style="text-align: center;">
                    <table style="margin: 0 auto;">
                        <tr style="text-align: center;">
                            <td class="label">
                                {{ $cetakpdf->nama_driver }}
                            </td>
                        </tr>
                        <tr>
                            <td class="separator" colspan="2"><span></span></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td class="label">Sopir</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: center;">
                    <table style="margin: 0 auto;">
                        <tr style="text-align: center;">
                            <td class="label">
                                DJOHAN WAHYUDI
                            </td>
                        </tr>
                        <tr>
                            <td class="separator" colspan="2"><span></span></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td class="label">Finance</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: center;">
                    <table style="margin: 0 auto;">
                        <tr style="text-align: center;">
                            <td class="label">
                                {{-- @if ($cetakpdf->user)
                                {{ $cetakpdf->user->karyawan->nama_lengkap }}
                                @else
                                user tidak ada
                                @endif --}}
                                {{ auth()->user()->karyawan->nama_lengkap }}
                            </td>
                        </tr>
                        <tr>
                            <td class="separator" colspan="2"><span></span></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td class="label">Admin</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="text-align: right; font-size:12px; margin-top:0px">
            <span style="font-style: italic;">Printed Date
                {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</span>
        </div>
        <br><br>
        <br>
        @endfor
        @endfor
</body>
{{-- @endforeach --}}

</html>