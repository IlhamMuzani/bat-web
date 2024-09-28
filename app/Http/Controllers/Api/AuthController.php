<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input kode_user dan password
        $validator = Validator::make($request->all(), [
            'kode_user' => 'required',
            'password' => 'required',
        ], [
            'kode_user.required' => 'Kode tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
        ]);

        // Jika validasi gagal, kembalikan pesan kesalahan
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return $this->response(false, $errors);
        }

        // Ambil nilai input
        $kode_user = $request->kode_user;
        $password = $request->password;

        // Cari pengguna berdasarkan kode_user dan departemen_id
        $user = User::where('kode_user', $kode_user)
            ->whereHas('karyawan', function ($query) {
                $query->where('departemen_id', 2);
            })
            ->first();

        // Cek apakah pengguna ditemukan
        if ($user) {
            // Verifikasi password pengguna
            if (password_verify($password, $user->password)) {
                // Kembalikan response sukses dengan informasi pengguna
                return $this->response(true, ['Berhasil login, Selamat Datang ' . $user->name], [$user]);
            } else {
                // Jika password tidak sesuai, kembalikan pesan kesalahan
                return $this->response(false, ['Kode atau password tidak sesuai!']);
            }
        } else {
            // Jika pengguna tidak ditemukan, kembalikan pesan kesalahan
            return $this->response(false, ['Pengguna tidak ditemukan!']);
        }
    }

    

    // public function detail($id)
    // {
    //     $user = User::where('id', $id)
    //         ->with(['karyawan', 'kendaraan', 'pengambilan_do' => function ($query) {
    //             $query->latest()->first(); // Mengambil yang terbaru
    //         }])
    //         ->first();

    //     if ($user) {
    //         return $this->response(TRUE, ['Berhasil menampilkan data'], [$user]);
    //     } else {
    //         return $this->response(FALSE, ['Gagal menampilkan detail!']);
    //     }
    // }

    public function detail($id)
    {
        $user = User::where('id', $id)
            ->with(['karyawan', 'kendaraan', 'pengambilan_do', 'latestpengambilan_do' => function ($query) {
                $query->with('kendaraan');
            }])
            ->first();
        
        if ($user) {
            return $this->response(TRUE, ['Berhasil menampilkan data'], [$user]);
        } else {
            return $this->response(FALSE, ['Gagal menampilkan detail!']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode_user' => 'required',
                'password' => 'required|min:6|confirmed',
            ],
            [
                'kode_user.required' => 'kode tidak boleh kosong',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimum 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai!',
            ]
        );

        // if (is_null($user)) {
        //     return $this->response(FALSE, 'Pendaftaran gagal, kode tidak ditemukan');
        // }
        $user = User::where('kode_user', $request->kode_user)->first();

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }


        User::where('kode_user', $request->kode_user)->update([
            'password' => bcrypt($request->password),
            'level' => 'admin',
        ]);

        if ($user) {
            return $this->response(TRUE, array('Berhasil melakukan pendaftaran'), array($user));
        } else {
            return $this->response(FALSE, 'Pendaftaran gagal, ' + $validator->errors()->all()[0]);
        }
    }



    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'kode_user' => 'required|exists:users,kode_user',
    //         'password' => 'required|min:6|confirmed',
    //     ], [
    //         'kode_user.required' => 'Kode user harus diisi!',
    //         'kode_user.exists' => 'Kode user tidak ditemukan!',
    //         'password.required' => 'Password tidak boleh kosong!',
    //         'password.min' => 'Password minimal 6 karakter!',
    //         'password.confirmed' => 'Konfirmasi password tidak sesuai!',
    //     ]);

    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->all();
    //         return $this->response(FALSE, $errors);
    //     }


    //     $user = User::where('kode_user', $request->kode_user)->update([
    //         'kode_user' => $request->kode_user,
    //         'password' => bcrypt($request->password),
    //         'level' => 'admin'
    //     ]);

    //     // ini sudah benar tpi tidak menggunakan update kode user
    //     // $user = User::where('kode_user', $request->kode_user)->update([
    //     //     'password' => bcrypt($request->password),
    //     //     'level' => 'admin'
    //     // ]);


    //     if ($user) {
    //         return $this->response(TRUE, array('Berhasil melakukan pendaftaran'), array($user));
    //     } else {
    //         return $this->response(FALSE, 'Pendaftaran gagal');
    //     }
    // }

    public function response($status, $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
}