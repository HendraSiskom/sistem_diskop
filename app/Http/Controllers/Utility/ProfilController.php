<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenggunaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Validator;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
            'data_jabatan' => DB::table('peran')->where(['id' => $pengguna->role])->first(),
            'daftar_kd_wil'  => DB::table('wilayah as a')->join('kd_wilayah as b', function ($join){
                $join->on('a.id_kd_wilayah', '=', 'b.id');
            })->where(['a.id' => $pengguna->wilayah])->select('a.id', 'a.nm_wilayah', 'b.kode', 'a.created_at')->first(),
        ];

        return view('utility.profil.index')->with($data);
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
                'password' => Hash::make($input['password']),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            DB::commit();
            return redirect()->back()->withStatus('Data Berhasil Disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput();
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
        //
    }
}
