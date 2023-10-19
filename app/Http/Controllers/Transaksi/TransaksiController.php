<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class TransaksiController extends Controller
{
    public function index()
    {
        $id_user = Auth::user()->id;
        $hasil = [
            'wilayah' => DB::table('pengguna as a')
                ->join('wilayah as b', 'b.id_kd_wilayah', '=', 'a.wilayah')
                ->select('a.wilayah', 'b.nm_wilayah')
                ->where(['a.id' => $id_user])->first(),
            'master_barang' => DB::table('master_barang')->get()
        ];
        return view('transaksi.index')->with($hasil);
    }

    function listbarang()
    {
        $id_user = Auth::user()->id;
        DB::beginTransaction();
        $hasil = DB::table('transaksi')
            ->where(['id_user' => $id_user])
            ->orderBy('tanggal_barang', 'ASC')
            ->get();
        DB::commit();
        return response()->json(['data' => $hasil]);
    }

    function simpanbarang(Request $request)
    {
        $id_user = Auth::user()->id;
        $username = Auth::user()->username;
        $validasi = Validator::make($request->all(), [
            'wilayah' => 'required',
            'varian_data' => 'required',
            'tglbarang' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ], [
            'wilayah.required' => 'wilayah Tidak Boleh Kosong',
            'varian_data.required' => 'Varian Barang Tidak Boleh Kosong',
            'tglbarang.required' => 'Tanggal Tidak Boleh Kosong',
            'harga.required' => 'Harga Tidak Boleh Kosong',
            'deskripsi.required' => 'Deskripsi Barang Tidak Boleh Kosong',
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        } else {
            DB::beginTransaction();
            // DB::statement(DB::raw('LOCK TABLES transaksi WRITE'));
            DB::table('transaksi')->raw('LOCK TABLES transaksi WRITE');
            DB::table('transaksi')->insert(
                [
                    'kode_barang' => $request->id_barang,
                    'nama_barang' => $request->varian_data,
                    'tanggal_barang' => $request->tglbarang,
                    'harga' => $request->harga,
                    'deskripsi' => $request->deskripsi,
                    'wilayah' => $request->wilayah,
                    'id_user' => $id_user,
                    'username' => $username,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            );
            DB::commit();
            if (DB::table('master_barang')->where('id', $request->id_barang)->exists()) {
                DB::beginTransaction();
                DB::table('master_barang')
                    ->where(['id' => $request->id_barang])
                    ->update(['status' => 1]);
                DB::commit();
            } elseif (DB::table('master_barang')->where('id', $request->id_barang)->doesntExist()) {
                DB::beginTransaction();
                DB::table('master_barang')
                    ->where(['id' => $request->id_barang])
                    ->update(['status' => 0]);
                DB::commit();
            }
            return response()->json(['success' => 'Berhasil disimpan']);
        }
    }

    function wherelistbarang(Request $request)
    {
        $id = $request->id;
        DB::beginTransaction();
        $hasil = DB::table('transaksi as a')
            ->join('master_barang as b', 'b.id', '=', 'a.kode_barang')
            ->select('a.id', 'a.kode_barang', 'a.nama_barang', 'a.tanggal_barang', 'a.harga', 'a.deskripsi', 'b.nama_satuan', 'b.nama_jnsbarang', 'nama_standarbarang')
            ->where(['a.id' => $id])
            ->first();
        DB::commit();
        return response()->json($hasil);
    }

    function updatebarang(Request $request)
    {
        // return dd($request->id_barangold);
        $id_user = Auth::user()->id;
        $username = Auth::user()->username;
        $validasi = Validator::make($request->all(), [
            'wilayah' => 'required',
            'varian_data' => 'required',
            'tglbarang' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ], [
            'wilayah.required' => 'wilayah Tidak Boleh Kosong',
            'varian_data.required' => 'Varian Barang Tidak Boleh Kosong',
            'tglbarang.required' => 'Tanggal Tidak Boleh Kosong',
            'harga.required' => 'Harga Tidak Boleh Kosong',
            'deskripsi.required' => 'Deskripsi Barang Tidak Boleh Kosong',
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        } else {
            DB::beginTransaction();
            DB::table('master_barang')
                ->where(['id' => $request->id_barang])
                ->update(['status' => '1']);

            $hasil = DB::table('transaksi')
                ->where('id', $request->id)
                ->update(
                    [
                        'kode_barang' => $request->id_barang,
                        'nama_barang' => $request->varian_data,
                        'tanggal_barang' => $request->tglbarang,
                        'harga' => $request->harga,
                        'deskripsi' => $request->deskripsi,
                        'wilayah' => $request->wilayah,
                        'id_user' => $id_user,
                        'username' => $username,
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                );

            if ($hasil) {
                $jml1 = DB::table('transaksi')->where('kode_barang', $request['id_barangold'])->count();
                // return dd($jml1);
                if ($jml1 == 0) {
                    // DB::beginTransaction();
                    DB::table('master_barang')
                        ->where(['id' => $request->id_barangold])
                        ->update(['status' => 0]);
                    // DB::commit();
                } elseif ($jml1 > 0) {
                    // DB::beginTransaction();
                    DB::table('master_barang')
                        ->where(['id' => $request->id_barangold])
                        ->update(['status' => 1]);
                    // DB::commit();
                }
            }
            DB::commit();

            return response()->json(['success' => 'Berhasil diupdate']);
        }
    }

    function hapusbarang(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::table('transaksi')
                ->where(['id' => $request['id_barang']])
                ->delete();
            DB::commit();
            $jml = DB::table('transaksi')->where('kode_barang', $request['kode_barang'])->count();
            if ($jml == 0) {
                DB::beginTransaction();
                DB::table('master_barang')
                    ->where(['id' => $request['kode_barang']])
                    ->update(['status' => 0]);
                DB::commit();
            }
            return response()->json(['pesan' => 'Berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['pesan' => $th]);
        }
    }
}
