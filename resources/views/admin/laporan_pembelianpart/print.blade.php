<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>faktur Pembelian Part</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'DOSVGA', monospace;
            color: black;
        }

        .container {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }

        .signature {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;

            /* Menghilangkan garis tepi tabel */
        }

        td {
            padding: 5px 10px;

            /* Menghilangkan garis tepi sel */

        }

        .label {
            text-align: left;
            width: 50%;
            border: none;
            /* Mengatur lebar kolom teks */
        }

        .value {
            text-align: right;
            width: 50%;
            border: none;
            /* Mengatur lebar kolom hasil */
        }

        .separator {
            text-align: center;
            font-weight: bold;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>FAKTUR PEMBELIAN PART - RANGKUMAN</h1>
    </div>

    <div class="text">
        @php
            $startDate = request()->query('tanggal_awal');
            $endDate = request()->query('tanggal_akhir');
        @endphp
        @if ($startDate && $endDate)
            <p>Periode:{{ $startDate }} s/d {{ $endDate }}</p>
        @else
            <p>Periode: Tidak ada tanggal awal dan akhir yang diteruskan.</p>
        @endif
    </div>
    <table>
        <tr>
            <th style="font-size: 10">Faktur Pembelian</th>
            <th style="font-size: 10">Tanggal</th>
            <th style="font-size: 10">Supplier</th>
            <th style="font-size: 10">Total</th>
        </tr>
        @foreach ($inquery as $pembelian_part)
            <tr>
                <td style="font-size: 10">{{ $pembelian_part->kode_pembelianpart }}</td>
                <td style="font-size: 10"> {{ $pembelian_part->tanggal_awal }}</td>
                <td style="font-size: 10"> {{ $pembelian_part->supplier->nama_supp }}</td>
                <td style="font-size: 10; text-align:right">
                    {{ number_format($pembelian_part->detail_part->sum('harga'), 0, ',', '.') }}
                </td>

            </tr>
        @endforeach
    </table>


    @php
        $total = 0;
    @endphp

    @foreach ($inquery as $pembelian_part)
        @php
            $total += $pembelian_part->detail_part->sum('harga');
        @endphp
    @endforeach

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br> <br>
    <div class="signature">
        <table>
            <tr>
                <td class="label">Total :</td>
                <td class="value">Rp. {{ number_format($total, 2) }}</td>
            </tr>
            <!-- Tambahkan baris-baris lainnya jika diperlukan -->
            <tr>
                <td class="separator" colspan="2">______________________________</td>
            </tr>
            <tr>
                <td class="label">Sub Total :</td>
                <td class="value">Rp. {{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>



</body>

</html>
