<?php

namespace App\Repository\User;
use Dotenv\Dotenv;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InfoKknRepo
{
    public function getInfoKkn($id_pengguna) : JsonResponse
    {
        try {
            $data=DB::table("tbl_calon_kkn")->whereRaw("id_mhs =?",[$id_pengguna])->selectRaw("*")->first();
            if(!empty($data)){
                $mhs=DB::table("tbl_mahasiswa as mhs")
                ->join("tbl_fakultas as f","f.id_fakultas","=","mhs.id_fakultas")
                ->join("tbl_jurusan as j","j.id_jurusan","=","mhs.id_jurusan")
                ->selectRaw("mhs.id_mhs,mhs.nim_mhs,mhs.nama_mhs,mhs.tempat_lahir_mhs,mhs.tgl_lahir_mhs,mhs.angkatan_mhs,
                f.nama_fakultas,j.nama_jurusan")->whereRaw("mhs.id_mhs=:id",["id"=>$id_pengguna])->first();
                $data->mhs=$mhs;
            }
            return response()->json(
                [
                    "message"=>"success",
                    "success"=>true,
                    "data"=>$data
                ]
            );
        }
        catch (\Throwable $th) {
            return response()->json(
                [
                    "message"=>"error ".$th->getMessage(),
                    "success"=>false,
                ]
            );
        }
    }

    public function checkPeriodeKkn(): JsonResponse
    {
        try {
            $data=[];
            $existdata=DB::table("tbl_periode_kkn as periode")->whereRaw("periode.status=?",[1])->select("*")->first();
            return response()->json(["message"=>"success","success"=>true,"data"=>$existdata],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error","success"=>false,],500);
        }
    }
}

