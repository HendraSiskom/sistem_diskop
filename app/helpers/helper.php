<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function filter_menu()
{
    $id = Auth::user()->id;

    $hak_akses = DB::table('pengguna as a')->join('pengguna_peran as b', function ($join) {
        $join->on('a.id', '=', 'b.id_pengguna');
    })->join('peran as c', function ($join) {
        $join->on('c.id', '=', 'b.id_peran');
    })->join('akses_peran as d', function ($join) {
        $join->on('c.id', '=', 'd.id_role');
    })->join('akses as e', function ($join) {
        $join->on('e.id', '=', 'd.id_akses');
    })->select('e.*')
        ->where(['a.id' => $id, 'e.urutan_menu' => '1'])
        ->orderBy('e.id')
        ->get();

    return $hak_akses;
}

function sub_menu()
{
    $id = Auth::user()->id;

    $hak_akses = DB::table('pengguna as a')->join('pengguna_peran as b', function ($join) {
        $join->on('a.id', '=', 'b.id_pengguna');
    })->join('peran as c', function ($join) {
        $join->on('c.id', '=', 'b.id_peran');
    })->join('akses_peran as d', function ($join) {
        $join->on('c.id', '=', 'd.id_role');
    })->join('akses as e', function ($join) {
        $join->on('e.id', '=', 'd.id_akses');
    })->select('e.*')
        ->where(['a.id' => $id, 'e.urutan_menu' => '2'])
        ->orderBy('e.urut_akses')
        ->orderBy('e.urut_akses2')
        ->get();

    return $hak_akses;
}

function sub_menu1()
{
    $id = Auth::user()->id;

    $hak_akses = DB::table('pengguna as a')->join('pengguna_peran as b', function ($join) {
        $join->on('a.id', '=', 'b.id_pengguna');
    })->join('peran as c', function ($join) {
        $join->on('c.id', '=', 'b.id_peran');
    })->join('akses_peran as d', function ($join) {
        $join->on('c.id', '=', 'd.id_role');
    })->join('akses as e', function ($join) {
        $join->on('e.id', '=', 'd.id_akses');
    })->select('e.*')
        ->where(['a.id' => $id, 'e.urutan_menu' => '3'])
        ->orderBy('e.urut_akses2')
        ->get();

    return $hak_akses;
}