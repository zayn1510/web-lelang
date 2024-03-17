<?php

namespace App\Repository\Akademik;

use App\Http\Requests\akademik\SyaratBerkasRequest;
use App\Models\akademik\BerkasSyaratModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SyaratBerkasRepository
{

    protected BerkasSyaratModel $model;

    public function __construct(BerkasSyaratModel $model)
    {
        $this->model = $model;
    }

    /**
     * Summary of loadDadta
     * @return array
     */
    public function loadDadta(): JsonResponse
    {
        try {

            $data = DB::table("tbl_syarat_berkas_kkn as sbk")->
                join("tbl_periode_kkn as pk", "pk.id_periode_kkn", "=", "sbk.periode_kkn")
                ->selectRaw("sbk.id,sbk.name_berkas,sbk.title_berkas,sbk.tipe_berkas,pk.tahun_akademik,pk.angkatan,sbk.periode_kkn")
                ->get();
            return response()->json([
                "data" => $data,
                "code" => 200,
                "success" => true,
                "message"=>"success"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "code" => $th->getCode(),
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function loadDataUser(int $idcalonkkn):JsonResponse
    {
        try {

            $data = DB::table('tbl_syarat_berkas_kkn AS sbk')
            ->join('tbl_periode_kkn AS pk', 'pk.id_periode_kkn', '=', 'sbk.periode_kkn')
            ->leftJoin('tbl_berkas_calon_kkn AS bck', function($join) use($idcalonkkn) {
                $join->on('bck.id_syarat_berkas', '=', 'sbk.id')
                     ->where('bck.id_calon_kkn', '=', $idcalonkkn);
            })
            ->select('sbk.id', 'sbk.name_berkas', 'sbk.title_berkas', 'sbk.tipe_berkas','bck.file',
                      'pk.tahun_akademik', 'pk.angkatan', 'sbk.periode_kkn', 'bck.id_berkas_calon_kkn')
            ->get();
            return response()->json([
                "data" => $data,
                "code" => 200,
                "success" => true,
                "message"=>"success"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "code" => $th->getCode(),
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function createData(SyaratBerkasRequest $syaratBerkasRequest): JsonResponse
    {
        try {

            $this->model->create($syaratBerkasRequest->validated());
            return response()->json([
                "code" => 200,
                "success" => true,
                "message" => "success",
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "code" => $th->getCode(),
                "message" => $th->getMessage(),
                "success" => false,
            ]);
        }
    }

    public function updateData(SyaratBerkasRequest $syaratBerkasRequest, int $id): JsonResponse
    {
        try {

            $this->model->whereRaw("id=?", [$id])->update($syaratBerkasRequest->validated());
            return response()->json([
                "code" => 200,
                "success" => true,
                "message" => "success",
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "code" => $th->getCode(),
                "message" => $th->getMessage(),
                "success" => false,
            ]);
        }
    }

    public function deleteData(int $id): JsonResponse
    {
        try {

            $this->model->whereRaw("id=?", [$id])->delete();
            return response()->json([
                "code" => 200,
                "success" => true,
                "message" => "success",
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "code" => $th->getCode(),
                "message" => $th->getMessage(),
                "success" => false,
            ]);
        }
    }

}
