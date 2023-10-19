<?php

namespace App\Http\Controllers\ListData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ListBarangController extends Controller
{
    public function index()
    {
        $hasil = [
            'master_jns_barang' => DB::table('master_jns_barang')->get(),
            'master_standar_barang' => DB::table('master_standar_barang')->get(),
            'master_satuan' => DB::table('master_satuan')->get(),
        ];
        return view('list_data.index')->with($hasil);
    }

    public function list_barang()
    {
        try {
            $id_user = Auth::user()->id;
            DB::beginTransaction();
            $hasil = DB::table('master_barang')
                ->orderBy('id', 'ASC')
                ->orderBy('kuantitas', 'ASC')
                ->get();
            DB::commit();
            return response()->json(['data' => $hasil]);
        } catch (\Throwable $th) {
            return response()->json(['data' => $th]);
        }
    }

    function simpanbarang(Request $request)
    {
        try {
            $id_user = Auth::user()->id;
            $validasi = Validator::make($request->all(), [
                'nama_barang' => 'required|unique:master_barang',
                'tmbhkuantitas' => 'required',
                'tmbhsatuan' => 'required',
                'tmbhjnsbarang' => 'required',
                'tmbhstdbarang' => 'required',
            ], [
                'nama_barang.required' => 'Nama Barang Tidak Boleh Kosong',
                'nama_barang.unique' => 'Nama Barang Sudah ada',
                'tmbhkuantitas.required' => 'Kuantitas Tidak Boleh Kosong',
                'tmbhsatuan.required' => 'Satuan Tidak Boleh Kosong',
                'tmbhjnsbarang.required' => 'Jenis Barang Tidak Boleh Kosong',
                'tmbhstdbarang.required' => 'Standar Barang Tidak Boleh Kosong',
            ]);
            if ($validasi->fails()) {
                return response()->json(['errors' => $validasi->errors()]);
            }
            // return;
            else {
                DB::beginTransaction();
                // DB::statement(DB::raw('LOCK TABLES master_barang WRITE'));
                DB::table('master_barang')->raw('LOCK TABLES master_barang WRITE');
                DB::table('master_barang')->insert([
                    'nama_barang' => $request->nama_barang,
                    'kuantitas' => $request->tmbhkuantitas,
                    'nama_satuan' => $request->tmbhsatuan,
                    'nama_jnsbarang' => $request->tmbhjnsbarang,
                    'nama_standarbarang' => $request->tmbhstdbarang,
                    'id_satuan' => $request->tmbh_satuan,
                    'id_jnsbarang' => $request->tmbh_jnsbarang,
                    'id_standarbarang' => $request->tmbh_stdbarang,
                    'status' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                DB::commit();
                return response()->json(['success' => 'Berhasil disimpan']);
            }
        } catch (\Throwable $th) {
            // DB::rollBack();
            return response()->json(['success' => $th]);
        }
    }

    function editbarang(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'nama_barang' => 'required|unique:master_barang',
                'editkuantitas' => 'required',
                'editsatuan' => 'required',
                'editjnsbarang' => 'required',
                'editstdbarang' => 'required',
            ], [
                'nama_barang.required' => 'Kode Wilayah Tidak Boleh Kosong',
                'nama_barang.unique' => 'Nama Barang Sudah Ada',
                'editkuantitas.required' => 'Kuantitas Tidak Boleh Kosong',
                'editsatuan.required' => 'Satuan Tidak Boleh Kosong',
                'editjnsbarang.required' => 'Jenis Barang Tidak Boleh Kosong',
                'editstdbarang.required' => 'Standar Barang Tidak Boleh Kosong',
            ]);
            if ($validasi->fails()) {
                return response()->json(['errors' => $validasi->errors()]);
            } else {
                DB::beginTransaction();
                DB::table('master_barang')
                    ->where(['id' => $request['id_barang']])
                    ->update([
                        'nama_barang' => $request->nama_barang,
                        'kuantitas' => $request->editkuantitas,
                        'nama_satuan' => $request->editsatuan,
                        'nama_jnsbarang' => $request->editjnsbarang,
                        'nama_standarbarang' => $request->editstdbarang,
                        'id_satuan' => $request->edit_id_satuan,
                        'id_jnsbarang' => $request->edit_jnsbarang,
                        'id_standarbarang' => $request->edit_stdbarang,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                DB::commit();
                return response()->json(['success' => 'Berhasil diupdate']);
            }
        } catch (\Throwable $th) {
            // DB::rollBack();
            return response()->json(['success' => $th]);
        }
    }

    function hapusbarang(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::table('master_barang')
                ->where(['id' => $request['id_barang']])
                ->delete();
            DB::commit();
            return response()->json(['pesan' => 'Berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['pesan' => $th]);
        }
    }
}
