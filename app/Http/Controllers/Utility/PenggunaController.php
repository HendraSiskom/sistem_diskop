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
            'daftar_kd_wil'  => DB::table('wilayah')->join('kd_wilayah', function ($join){
                $join->on('wilayah.id_kd_wilayah', '=', 'kd_wilayah.id');
            })->where(['wilayah.status' => '0'])->get(),
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
        // try {
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
            // DB::table('pengguna_peran1')->insert([
            //     'id_pengguna' => $id,
            //     'id_peran' => $request['peran'],
            // ]);

            DB::commit();
            return redirect()->route('pengguna.index')->withStatus('Data Berhasil Disimpan');
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return redirect()->back()->withInput();
        // }
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

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("pengguna.edit", Crypt::encryptString($row->id)) . '" class="btn btn-success btn-xs" style="margin-right:4px" title="Edit Data"><i class="fas fa-edit"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapusPengguna(\'' . $row->id . '\', \'' . Auth::user()->id . '\');" data-id="\'' . $row->id . '\'" class="btn btn-danger btn-xs" style="margin-right:4px" title="Hapus Data"><i class="fas fa-trash-alt"></i></a>';
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
