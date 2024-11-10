<?php

namespace App\Http\Controllers\Pelanggan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Kendaraan;
use App\Models\Pengambilan_do;
use App\Models\Spk;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class MonitoringkendaraanController extends Controller
{

    // sudah benar kurang status perjalanan 
    public function index(Request $request)
    {
        $user = auth()->user();


        $do_kendaraans = Pengambilan_do::where('userpelanggan_id', $user->id)
            ->whereNotIn('status', ['unpost', 'posting', 'selesai'])
            ->with('kendaraan')
            ->get();

        // Tentukan apakah ada pencarian atau tidak
        if ($request->has('kendaraan_id') && $request->kendaraan_id !== '') {

            // Update kendaraan hanya jika ada pencarian
            $waktuPerjalananIsi = now();
            foreach ($do_kendaraans as $do_kendaraan) {
                $kendaraan = $do_kendaraan->kendaraan;
                if ($kendaraan) {
                    $waktuTungguMuat = $kendaraan->updated_at;
                    $jarakWaktu = $waktuTungguMuat->diffInSeconds($waktuPerjalananIsi);

                    $timerParts = explode(' ', $kendaraan->timer);
                    $hari = (int)$timerParts[0];
                    $jamMenit = explode(':', $timerParts[1]);
                    $jam = (int)$jamMenit[0];
                    $menit = (int)$jamMenit[1];

                    $totalDetik = ($hari * 24 * 60 * 60) + ($jam * 60 * 60) + ($menit * 60) + $jarakWaktu;

                    $hariBaru = floor($totalDetik / (24 * 60 * 60));
                    $totalDetik %= (24 * 60 * 60);
                    $jamBaru = floor($totalDetik / (60 * 60));
                    $totalDetik %= (60 * 60);
                    $menitBaru = floor($totalDetik / 60);

                    $formattedTimer = sprintf('%d %02d:%02d', $hariBaru, $jamBaru, $menitBaru);
                    $kendaraan->update(['timer' => $formattedTimer]);

                    $this->updateDataFromAPI($kendaraan);
                }
            }

            // Query pengambilan_do berdasarkan pencarian kendaraan_id
            $pengambilanDoQuery = Pengambilan_do::where('userpelanggan_id', $user->id)
                ->whereNotIn(
                    'status',
                    ['unpost', 'posting', 'selesai']
                )
                ->with('kendaraan');

            if ($request->kendaraan_id != 'all') {
                $pengambilanDoQuery->where('kendaraan_id', $request->kendaraan_id);
            }

            $pengambilan_do = $pengambilanDoQuery->get()->sortBy(function ($do) {
                return (int) filter_var($do->kendaraan->no_kabin, FILTER_SANITIZE_NUMBER_INT);
            });
        } else {
            // Jika tidak ada pencarian, set $pengambilan_do ke koleksi kosong
            $pengambilan_do = collect();
        }

        return view('pelanggan.monitoring_kendaraan.index', compact('do_kendaraans', 'pengambilan_do'));
    }

    private function updateDataFromAPI($kendaraan)
    {
        try {
            $client = new Client();
            $response = $client->post('https://vtsapi.easygo-gps.co.id/api/Report/lastposition', [
                'headers' => [
                    'accept' => 'application/json',
                    'token' => 'ADB4E5DFAAEA4BA1A6A8981FEF86FAA9',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'list_vehicle_id' => [$kendaraan->list_vehicle_id],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $vehicle = $data['Data'][0] ?? null;
            if ($vehicle && $vehicle['vehicle_id'] == $kendaraan->list_vehicle_id) {
                $kendaraan->update([
                    'km' => $vehicle['odometer'] ?? $kendaraan->km,
                    'latitude' => $vehicle['lat'] ?? null,
                    'longitude' => $vehicle['lon'] ?? null,
                    'lokasi' => $vehicle['addr'] ?? null,
                    'status_kendaraan' => $vehicle['currentStatusVehicle']['status'] ?? $kendaraan->status_kendaraan,
                ]);
            }
        } catch (\Exception $e) {
            // Handle API errors
        }
    }

    public function update_latlong($id)
    {
        $kendaraan = Kendaraan::find($id);
        if ($kendaraan) {
            try {
                $client = new \GuzzleHttp\Client();
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
                        $latitude = $data['Data'][0]['lat'] ?? null;
                        $longitude = $data['Data'][0]['lon'] ?? null;

                        if ($odometer > 0) {
                            $kendaraan->km = $odometer;
                        }
                        if ($latitude !== null && $longitude !== null) {
                            $kendaraan->latitude = $latitude;
                            $kendaraan->longitude = $longitude;
                        }

                        // Simpan perubahan ke database
                        $kendaraan->save();

                        return response()->json([
                            'success' => true,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak terhubung ke GPS.'
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Kendaraan tidak ditemukan.',
        ]);
    }
}