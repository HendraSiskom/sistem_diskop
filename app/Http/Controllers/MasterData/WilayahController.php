<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class WilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'daftar_kd_wil' => DB::table('kd_wilayah')->where(['status' => '0'])->orderBy('created_at', 'ASC')->get(),
        ];
        return view('master_data.wilayah.index')->with($data);
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
    public function show()
    {
        $data = DB::table('wilayah as a')->join('kd_wilayah as b', function ($join) {
            $join->on('a.id_kd_wilayah', '=', 'b.id');
        })->orderBy('a.created_at', 'ASC')
            ->select('a.id', 'a.nm_wilayah', 'b.kode', 'a.status', 'a.created_at')->get();

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
        $data = DB::table('wilayah as a')->join('kd_wilayah as b', function ($join) {
            $join->on('a.id_kd_wilayah', '=', 'b.id');
        })->orderBy('a.created_at', 'ASC')
            ->where(['a.id' => $id])
            ->select('a.id', 'a.id_kd_wilayah','a.nm_wilayah', 'b.kode', 'a.status', 'a.created_at')->first();
        
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
