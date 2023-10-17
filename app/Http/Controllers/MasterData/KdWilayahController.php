<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KdWilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master_data.kd_wilayah.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validasi = Validator::make($request->all(), [
                'kode' => 'required|min:5|numeric|unique:kd_wilayah',
            ], [
                'kode.required'        => 'Kode Wilayah Tidak Boleh Kosong.',
                'kode.unique'          => 'Kode Wilayah Sudah Terdaftar.',
                'kode.min'             => 'Kode Wilayah Harus 5 Karakter.',
            ]);

            if ($validasi->fails()) {
                return response()->json(['errors' => $validasi->errors()]);
            } else {
                DB::table('kd_wilayah')->insert([
                    'kode'       => $request['kode'],
                    'status'     => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                DB::commit();
                return response()->json(['success' => 'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $data = DB::table('kd_wilayah')->orderBy('created_at', 'ASC')->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->status == '0') {
                $btn = '<a href="javascript:void(0);" onclick="functionShowData(\'' . $row->id . '\');" class="btn btn-warning btn-xs" style="margin-right:4px" title="Edit Data"><i class="fas fa-edit"></i></a>';
                $btn .= '<a href="javascript:void(0);" onclick="functionDeleteData(\'' . $row->id . '\');" class="btn btn-danger btn-xs" style="margin-right:4px" title="Hapus Data"><i class="fas fa-trash-alt"></i></a>';
                return $btn;
            }
        })->rawColumns(['aksi'])->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DB::table('kd_wilayah')->where(['id' => $id])->first();
        return response()->json(['result' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validasi = Validator::make($request->all(), [
                'kode' => "required|min:5|numeric|unique:kd_wilayah,kode,$id"
            ], [
                'kode.required'        => 'Kode Wilayah Tidak Boleh Kosong.',
                'kode.unique'          => 'Kode Wilayah Sudah Terdaftar.',
                'kode.min'             => 'Kode Wilayah Harus 5 Karakter.',
            ]);

            if ($validasi->fails()) {
                return response()->json(['errors' => $validasi->errors()]);
            } else {
                DB::table('kd_wilayah')->where(['id' => $id])->update([
                    'kode'       => $request['kode'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                DB::commit();
                return response()->json(['success' => 'Data Berhasil Diubah']);
            }
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::table('kd_wilayah')->where(['id' => $request->id])->delete();
            DB::commit();
            return response()->json([
                'message' => '1'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }
}
