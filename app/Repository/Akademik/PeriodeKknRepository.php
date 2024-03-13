<?php

/**
 * Summary of namespace App\Repository\Akademik
 */
namespace App\Repository\Akademik;

use App\Http\Requests\akademik\PeriodeRequest;
use App\Http\Resources\api\akademik\PeriodekknResource;
use App\Models\akademik\PeriodekknModel;

/**
 * Summary of PeriodeKKnRepository
 */
class PeriodeKknRepository
{

    public static function loadData()
    {
        try {
            return response()->json([

                "data" => PeriodekknResource::collection(PeriodekknModel::all()),
                "message" => "Success",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "data" => [],
                "message" => "error " . $th->getMessage(),
            ]);
        }
    }
    public static function createData(PeriodeRequest $request)
    {

        $allowrequest = $request->only("tahun_akademik", "angkatan", "tgl_akademik", "status", "status_pendaftaran", "tgl_mulai", "tgl_selesai");
        try {
            PeriodekknModel::create($allowrequest);
            return response()->json([
                "message" => "Success",
                "status" => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Failed " . $th->getMessage(),
                "status" => false,
            ]);
        }
    }

    public static function updateData(PeriodeRequest $request)
    {
        try {
            $allowrequest = $request->only("tahun_akademik", "angkatan", "tgl_akademik", "status", "status_pendaftaran", "tgl_mulai", "tgl_selesai");
            PeriodekknModel::where("id_periode_kkn", $request->id_periode_kkn)->update($allowrequest);
            return response()->json([
                "message" => "Success",
                "status" => true,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Failed " . $th->getMessage(),
                "status" => false,
            ]);
        }
    }

    public static function deleteData($id)
    {
        $data = PeriodekknModel::find($id);
        if ($data) {PeriodekknModel::find($id)->delete();return ["message" => "Success", "status" => true];}
        return ["message" => "Failed", "status" => false];
    }

}
