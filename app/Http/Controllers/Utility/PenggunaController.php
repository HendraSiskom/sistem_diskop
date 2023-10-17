<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenggunaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('utility.pengguna.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'daftar_kd_wil'  => DB::table('wilayah as a')->join('kd_wilayah as b', function ($join){
                $join->on('a.id_kd_wilayah', '=', 'b.id');
            })->orderBy('a.created_at', 'ASC')->select('a.id', 'a.nm_wilayah', 'b.kode', 'a.created_at')->get(),
            'daftar_peran'  => DB::table('peran')->get(),
        ];

        return view('utility.pengguna.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PenggunaRequest $request)
    {
        $input = array_map('htmlentities', $request->validated());
        try {
            DB::beginTransaction();
            $id = DB::table('pengguna')->insertGetId([
                'username' => $input['username'],
                'password' => Hash::make($input['password']),
                'nama' => $input['nama'],
                'wilayah' => $request['wilayah'],
                'role' => $request['peran'],
                'status_aktif' => '0',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            DB::table('pengguna_peran')->insert([
                'id_pengguna' => $id,
                'id_peran' => $request['peran'],
            ]);

            DB::table('wilayah')->where(['id' => $request['wilayah']])->update([
                'status'       => '1',
            ]);

            DB::commit();
            return redirect()->route('pengguna.index')->withStatus('Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function load_data()
    {
        $data = DB::table('pengguna')->get();

        $data = DB::table('pengguna as a')->Select('a.*',
            DB::raw("(select nm_wilayah from wilayah b where a.wilayah = b.id) as wilayah"),
            DB::raw("(select kode from kd_wilayah b JOIN wilayah c on b.id = c.id_kd_wilayah where c.id =a.wilayah) as kode"),
            DB::raw("(select nm_role from peran b where a.role = b.id) as jabatan")
        )->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            if ($row->status_aktif == '1') {
                $btn = '<div class="form-check form-switch">
                    <a href="' . route("pengguna.edit", Crypt::encryptString($row->id)) . '" class="btn btn-warning btn-xs" style="margin-right:4px" title="Edit Data"><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0);" onclick="hapusPengguna(\'' . $row->id . '\', \'' . Auth::user()->id . '\');" data-id="\'' . $row->id . '\'" class="btn btn-danger btn-xs" style="margin-right:4px" title="Hapus Data"><i class="fas fa-trash-alt"></i></a>    
                    <input type="checkbox" class="form-check-input" id="' . $row->id . '" onChange="ubahStatus(\'' . $row->id . '\', \'' . $row->status_aktif . '\');" checked>
                    <label class="custom-control-label" for="' . $row->id . '"></label>
                    </div>';
            } else {
                $btn = '<div class="form-check form-switch">
                    <a href="' . route("pengguna.edit", Crypt::encryptString($row->id)) . '" class="btn btn-warning btn-xs" style="margin-right:4px" title="Edit Data"><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0);" onclick="hapusPengguna(\'' . $row->id . '\', \'' . Auth::user()->id . '\');" data-id="\'' . $row->id . '\'" class="btn btn-danger btn-xs" style="margin-right:4px" title="Hapus Data"><i class="fas fa-trash-alt"></i></a>    
                    <input type="checkbox" class="form-check-input" id="' . $row->id . '" onChange="ubahStatus(\'' . $row->id . '\', \'' . $row->status_aktif . '\');">
                    <label class="custom-control-label" for="' . $row->id . '"></label>
                    </div>';
            }
            return $btn;
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
        $id = Crypt::decryptString($id);
        $pengguna = DB::table('pengguna')->where(['id' => $id])->first();
        $data = [
            'data_pengguna' => $pengguna,
            'daftar_peran' => DB::table('peran')->get(),
            'daftar_kd_wil'  => DB::table('wilayah as a')->join('kd_wilayah as b', function ($join){
                $join->on('a.id_kd_wilayah', '=', 'b.id');
            })->orderBy('a.created_at', 'ASC')->select('a.id', 'a.nm_wilayah', 'b.kode', 'a.created_at')->get(),
        ];

        return view('utility.pengguna.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PenggunaRequest $request, $id)
    {
        $input = array_map('htmlentities', $request->validated());
        try {
            DB::beginTransaction();
            DB::table('pengguna')->where(['id' => $id])->update([
                'username' => $input['username'],
                'nama' => $input['nama'],
                'password' => Hash::make($input['password']),
                'wilayah' => $request['wilayah'],
                'role' => $request['peran'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            DB::table('pengguna_peran')->where(['id_pengguna' => $id])->update([
                'id_peran' => $request['peran'],
            ]);
            DB::commit();
            return redirect()->route('pengguna.index')->withStatus('Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $status_aktif = $request->status_aktif;

        DB::beginTransaction();
        try {
            if ($status_aktif == '1') {
                DB::table('pengguna')->where(['id' => $id])
                    ->update([
                        'status_aktif' => '0',
                    ]);
                DB::commit();
                return response()->json([
                    'message' => '1',
                ]);
            } else {
                DB::table('pengguna')->where(['id' => $id])
                    ->update([
                        'status_aktif' => '1',
                    ]);
                DB::commit();
                return response()->json([
                    'message' => '2',
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => '0'
            ]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $dataCek = DB::table('pengguna')->where(['id' => $id])->first();
            DB::table('pengguna')->where(['id' => $id])->delete();
            DB::table('pengguna_peran')->where(['id_pengguna' => $id])->delete();
            $count = DB::table('pengguna')->where(['wilayah' => $dataCek->wilayah])->count();
            if ($count < 1) {
                DB::table('wilayah')->where(['id' => $dataCek->wilayah])->update([
                    'status'       => '0',
                ]);
            }
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
