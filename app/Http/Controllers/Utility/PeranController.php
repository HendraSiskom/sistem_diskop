<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Http\Requests\PeranRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class PeranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('utility.peran.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'daftar_hak_akses' => DB::table('akses')
                ->where(['urutan_menu' => '1'])
                ->get(),
            'daftar_hak_akses1' => DB::table('akses')
                ->get(),
        ];

        return view('utility.peran.create')->with($data);
    }

    public function load_data()
    {
        $data = DB::table('peran')->get();

        return DataTables::of($data)->addIndexColumn()->addColumn('aksi', function ($row) {
            $btn = '<a href="' . route("peran.edit", Crypt::encryptString($row->id)) . '" class="btn btn-warning btn-xs" style="margin-right:4px" title="Edit Data"><i class="fas fa-edit"></i></a>';
            $btn .= '<a href="' . route("peran.show", Crypt::encryptString($row->id)) . '" class="btn btn-info btn-xs" style="margin-right:4px" title="Lihat Data"><i class="fas fa-info-circle"></i></a>';
            $btn .= '<a href="javascript:void(0);" onclick="hapusPeran(\'' . $row->id . '\', \'' . Auth::user()->role . '\');" data-id="\'' . $row->id . '\'" class="btn btn-danger btn-xs" style="margin-right:4px" title="Hapus Data"><i class="fas fa-trash-alt"></i></a>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PeranRequest $request)
    {
        $input = $request->validated();
        $data['role'] = $input['role'];
        $data['nm_role'] = $input['nm_role'];
        $hak_akses = $input['hak_akses'];
        $input = array_map('htmlentities', $data);
        $input_akses = array_map('htmlentities', $hak_akses);

        try {
            DB::beginTransaction();
            $id = DB::table('peran')
                ->insertGetId([
                    'role' => $input['role'],
                    'nm_role' => $input['nm_role'],
                ]);
            if (isset($input_akses)) {
                DB::table('akses_peran')
                    ->insert(array_map(function ($value) use ($id) {
                        return ['id_akses' => $value, 'id_role' => $id, "username_created" => Auth::user()->nama, "created_at" => date('Y-m-d H:i:s')];
                    }, $input_akses));
            }
            DB::commit();
            return redirect()->route('peran.index')->withStatus('Data Berhasil Disimpan');
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
        $id = Crypt::decryptString($id);
        $peran = DB::table('peran')->where(['id' => $id])->first();
        $data = [
            'daftar_hak_akses' => DB::table('akses_peran')->join('akses', function ($join) {
                $join->on('akses_peran.id_akses', '=', 'akses.id');
            })->where(['akses_peran.id_role' => $peran->id])->get()
        ];

        return view('utility.peran.show')->with($data);
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
        $peran = DB::table('peran')->where(['id' =>  $id])->first();
        $daftar_hak_akses = DB::table('akses_peran')
            ->select('akses_peran.id_akses')->join('akses', function ($join) {
                $join->on('akses_peran.id_akses', '=', 'akses.id');
            })->where(['id_role' => $peran->id])->get();

        $array = json_decode(json_encode($daftar_hak_akses), true);
        $array = array_column($array, "id_akses");
        $data = [
            'data_peran' => DB::table('peran')->where(['id' => $id])->first(),
            'hak_akses1' => $array,
            'daftar_hak_akses' => DB::table('akses')
                ->where(['urutan_menu' => '1'])
                ->orderBy('id')
                ->get(),
            'daftar_hak_akses1' => DB::table('akses')
                ->orderBy('urut_akses')
                ->orderBy('urut_akses2')
                ->get(),
        ];
        return view('utility.peran.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PeranRequest $request, $id)
    {
        $input = $request->validated();
        $data['role'] = $input['role'];
        $data['nm_role'] = $input['nm_role'];
        $hak_akses = $input['hak_akses'];
        $input = array_map('htmlentities', $data);
        $input_akses = array_map('htmlentities', $hak_akses);

        DB::beginTransaction();
        try {
            DB::table('peran')->where(['id' => $id])->update([
                'role' => $input['role'],
                'nm_role' => $input['nm_role'],
            ]);

            DB::table('akses_peran')->where(['id_role' => $id])->delete();

            if (isset($input_akses)) {
                DB::table('akses_peran')->insert(array_map(function ($value) use ($id) {
                    return [
                        'id_akses' => $value,
                        'id_role' => $id,
                        "username_created" => Auth::user()->nama,
                        "created_at" => date('Y-m-d H:i:s')
                    ];
                }, $input_akses));
            }
            DB::commit();
            return redirect()->route('peran.index')->withStatus('Data Berhasil Disimpan');
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
        try {
            DB::beginTransaction();
            DB::table('peran')->where(['id' => $id])->delete();
            DB::table('akses_peran')->where(['id_role' => $id])->delete();

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
