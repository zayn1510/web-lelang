<?php
namespace App\Repository\Akademik;

use App\lib\Uploadfile;
use App\Models\mahasiswa\berkasCalonKknModel;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BerkasRepository
{

    public static function upload_berkas_calon_kkn(Request $request)
    {
        try {
            DB::beginTransaction();
            $result = Uploadfile::upload_berkas_calon_kkn($request);
            $check = $result["success"];
            if ($check) {

                $idmhs=$request->id_mhs;
                $data = [
                    "file" => $result["data"],
                    "id_syarat_berkas" => $request->id_syarat_berkas,
                ];
                $calonkkn = DB::table("tbl_calon_kkn")->whereRaw("id_mhs=?",[$idmhs])->first();
                $data["id_calon_kkn"] = $calonkkn->id_calon_kkn;
                berkasCalonKknModel::create($data);
                DB::commit();
                return ["message" => "Upload File Berhasil", "success" => true, "data" => $result["data"]];
            }
            return ["message" => "Upload File Gagal", "success" => false, "data" => $result["data"]];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(["message" => "Invalid ID", "success" => false], 500);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["message" => "Something Error To Query Results " . $th->getMessage(), "success" => false], 500);
        }

    }

}
