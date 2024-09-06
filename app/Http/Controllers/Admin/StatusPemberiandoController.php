<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Pengambilan_do;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class StatusPemberiandoController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $spks = Pengambilan_do::whereNotNull('spk_id');

        if ($status) {
            $spks->where('status', $status);
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $spks->whereBetween('tanggal_awal', [$tanggal_awal, $tanggal_akhir]);
        } elseif ($tanggal_awal) {
            $spks->where('tanggal_awal', '>=', $tanggal_awal);
        } elseif ($tanggal_akhir) {
            $spks->where('tanggal_awal', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal hari ini
            $spks->whereDate('tanggal_awal', Carbon::today());
        }

        $spks->orderBy('id', 'DESC');
        $spks = $spks->get();

        return view('admin.status_pemberiando.index', compact('spks'));
    }

    public function show($id)
    {
        $cetakpdf = Pengambilan_do::find($id);
        $kendaraan = Kendaraan::find($cetakpdf->kendaraan_id);
        $odometer = null; // Inisialisasi variabel $odometer

        if ($kendaraan) {
            $client = new Client();
            $response = $client->post('https://vtsapi.easygo-gps.co.id/api/Report/lastposition', [
                'headers' => [
                    'accept' => 'application/json',
                    'token' => 'ADB4E5DFAAEA4BA1A6A8981FEF86FAA9',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'list_vehicle_id' => [$kendaraan->list_vehicle_id],
                    'list_nopol' => [],
                    'list_no_aset' => [],
                    'geo_code' => [],
                    'min_lastupdate_hour' => null,
                    'page' => 0,
                    'encrypted' => 0,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['Data'][0]['vehicle_id'])) {
                $vehicleId = $data['Data'][0]['vehicle_id'];

                if ($vehicleId === $kendaraan->list_vehicle_id) {
                    $odometer = intval($data['Data'][0]['odometer'] ?? 0);

                    if ($odometer > 0) {
                        $kendaraan->km = $odometer;
                        $kendaraan->save();
                    }
                }
            }
        }

        // Pastikan variabel $odometer dimasukkan dalam compact
        return view('admin.status_pemberiando.show', compact('cetakpdf', 'odometer'));
    }
    
}