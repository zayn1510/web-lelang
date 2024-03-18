<?php

namespace App\Repository\Mahasiswa;

use App\Http\Requests\mahasiswa\BerkasRequest;
use App\Http\Requests\mahasiswa\CalonKknRequest;
use App\Http\Resources\api\mahasiswa\CalonKknResource;
use App\Models\mahasiswa\berkasCalonKknModel;
use App\Models\mahasiswa\CalonkknModel;
use App\Models\mahasiswa\MahasiswaModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CalonkknRepository
{

    public static function get_data_by_periode($id)
    {
        try {

            $result = DB::table("tbl_calon_kkn as ck")->
                join("tbl_periode_kkn as pk", "pk.id_periode_kkn", "=", "ck.id_periode_kkn")->
                join("tbl_mahasiswa as mhs", "mhs.id_mhs", "=", "ck.id_mhs")->
                whereRaw("pk.id_periode_kkn=:id_periode", ["id_periode" => $id])->
                select("ck.id_calon_kkn", "mhs.nama_mhs", "mhs.nim_mhs", "ck.id_mhs", "ck.email", "ck.nomor_hp",
                "ck.kode_calon_kkn", "ck.ukuran_baju", "ck.kecamatan", "ck.kabupaten", "ck.desa",
                "ck.tgl_daftar", "ck.status", "ck.id_periode_kkn",
                "pk.tahun_akademik", "pk.angkatan", "pk.tgl_akademik", "mhs.tempat_lahir_mhs", "mhs.tgl_lahir_mhs",
                "mhs.angkatan_mhs"
            )->get();

            if (empty($result)) {
                return response()->json(["message" => "data not found", "success" => false, "data" => $result], 500);
            }
            $data = CalonKknResource::collection($result);
            return response()->json(["message" => "Success", "success" => true, "data" => $data], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error in database " . $th->getMessage(), "success" => false, "data" => []], 500);
        }
    }

    /**
     * Summary of update_data_calon_kkn_any
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function update_data_calon_kkn_any(Request $request): JsonResponse
    {

        try {
            DB::beginTransaction();
            $dataupdate = $request->except("id_calon_kkn");
            $calon = CalonkknModel::findOrFail($request->only("id_calon_kkn"))->first();
            $angkatan = DB::table("tbl_periode_kkn")->where("id_periode_kkn", $calon["id_periode_kkn"])->first();
            $mhs = MahasiswaModel::where("id_mhs", $calon["id_mhs"])->select("*")->first();
            CalonkknModel::where("id_calon_kkn", $request->only("id_calon_kkn"))->update($dataupdate);
            $datamail = [
                "subjek" => "KONFIRMASI PENERIMAAN PESERTA KKN",
                "to" => $calon->email,
                "nim" => $mhs["nim_mhs"],
                "nama" => $mhs["nama_mhs"],
                "angkatan" => $angkatan->angkatan,
                "tahun_akademik" => $angkatan->tahun_akademik,
            ];

            \App\lib\Mailer::send($datamail);
            DB::commit();
            return response()->json(["message" => "Success", "success" => true], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(["message" => "Invalid ID", "success" => false], 500);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["message" => "Error in " . $th->getMessage(), "success" => false], 500);
        }
    }

    /**
     * Summary of get_data_berkas_calon
     * @param mixed $id
     * @return JsonResponse|mixed
     */
    public static function get_data_berkas_calon($id): JsonResponse
    {

        try {
            $result = DB::table("tbl_berkas_calon_kkn")->where("id_berkas_calon_kkn", $id)->select("*")->first();
            if (empty($result)) {
                return response()->json(["message" => "failed id", "success" => false], 500);
            }

            return response()->json(["message" => "success", "success" => true, "data" => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error in server", "success" => false]);
        }
    }
    /**
     * Summary of get_data_calon_kkn
     * @return \Illuminate\Http\JsonResponse
     */
    public static function get_data_calon_kkn(): JsonResponse
    {

        try {

            $result = DB::table("tbl_calon_kkn as ck")->
                join("tbl_periode_kkn as pk", "pk.id_periode_kkn", "=", "ck.id_periode_kkn")->
                join("tbl_mahasiswa as mhs", "mhs.id_mhs", "=", "ck.id_mhs")->
                where("pk.status", 1)->
                select("ck.id_calon_kkn", "mhs.nama_mhs", "mhs.nim_mhs", "ck.id_mhs", "ck.email", "ck.nomor_hp",
                "ck.kode_calon_kkn", "ck.ukuran_baju", "ck.kecamatan", "ck.kabupaten", "ck.desa",
                "ck.tgl_daftar", "ck.status", "ck.id_periode_kkn",
                "pk.tahun_akademik", "pk.angkatan", "pk.tgl_akademik", "mhs.tempat_lahir_mhs", "mhs.tgl_lahir_mhs",
                "mhs.angkatan_mhs"

            )->get();
            return response()->json([
                "data" => $result,
                "message" => "success",
                "success" => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "error " . $th->getMessage(),
                "success" => false,
            ]);
        }
    }

    /**
     * Summary of get_data_calon_kkn
     * @return \Illuminate\Http\JsonResponse
     */
    public static function get_data_calon_kkn_by_group(): JsonResponse
    {

        try {

            $result = DB::table("tbl_calon_kkn as ck")->
                join("tbl_periode_kkn as pk", "pk.id_periode_kkn", "=", "ck.id_periode_kkn")->
                join("tbl_mahasiswa as mhs", "mhs.id_mhs", "=", "ck.id_mhs")->
                join("tbl_fakultas as f", "f.id_fakultas", "=", "mhs.id_fakultas")->
                join("tbl_jurusan as j", "j.id_jurusan", "=", "mhs.id_jurusan")->
                leftJoin("tbl_detail_anggota_kkn as dak", "dak.id_calon_kkn", "=", "ck.id_calon_kkn")->
                where("pk.status", 1)->
                where("ck.group", 0)->
                select("ck.id_calon_kkn", "mhs.nama_mhs", "mhs.nim_mhs", "ck.id_mhs", "ck.email", "ck.nomor_hp",
                "ck.kode_calon_kkn", "ck.ukuran_baju", "ck.kecamatan", "ck.kabupaten", "ck.desa",
                "ck.tgl_daftar", "ck.status", "ck.id_periode_kkn",
                "pk.tahun_akademik", "pk.angkatan", "pk.tgl_akademik", "mhs.tempat_lahir_mhs", "mhs.tgl_lahir_mhs",
                "mhs.angkatan_mhs", "dak.id as id_detail", "f.nama_fakultas", "j.nama_jurusan"
            )->get();

            return response()->json([
                "data" => $result,
                "message" => "success",
                "success" => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "error " . $th->getMessage(),
                "success" => false,
            ]);
        }
    }

    /**
     * Summary of create_data_calon_kkn
     * @param \App\Http\Requests\mahasiswa\CalonKknRequest $calonKknRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public static function create_data_calon_kkn(CalonKknRequest $calonKknRequest, BerkasRequest $berkasRequest): JsonResponse
    {

        DB::beginTransaction();
        try {
            $periode = DB::table("tbl_periode_kkn")->where("status", 1)->select("id_periode_kkn")->first();
            $calonKknRequest["id_periode_kkn"] = $periode->id_periode_kkn;
            $allowinsertcalon = $calonKknRequest->only("id_mhs", "email", "nomor_hp", "ukuran_baju", "desa",
                "kecamatan", "kabupaten", "tgl_daftar", "kode_calon_kkn", "status", "id_periode_kkn");
            $allowinsertcalon["tgl_daftar"] = date("Y-m-d");
            DB::table("tbl_calon_kkn")->insert($allowinsertcalon);
            DB::commit();
            return response()->json(["message" => "success", "success" => true]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["message" => "error " . $th->getMessage(), "success" => false], 500);
        }
    }

    /**
     * Summary of update_ukuran_baju
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public static function update_ukuran_baju(Request $request)
    {
        $validate = \Validator::make($request->only("ukuran_baju"), ["ukuran_baju" => ["required", "string"]]);
        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors(), "success" => false]);
        }
        try {
            CalonkknModel::findOrFail($request->only("id_calon_kkn"));
            CalonkknModel::where("id_calon_kkn", $request->only("id_calon_kkn"))->update($request->only("ukuran_baju"));
            return response()->json(['message' => "Success", "success" => true]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => "Invalid id mhs", "success" => false]);
        }

    }

    /**
     * Summary of update_data_calon_kkn
     * @param \App\Http\Requests\mahasiswa\CalonKknRequest $calonKknRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public static function update_data_calon_kkn(CalonKknRequest $calonKknRequest): JsonResponse
    {
        try {
            $allowupdate = $calonKknRequest->except("id_calon_kkn");
            $query = CalonkknModel::where("id_calon_kkn", $calonKknRequest->only("id_calon_kkn"))->update($allowupdate);
            if ($query) {
                return response()->json(["message" => "success", "success" => true], 200);
            }

            return response()->json(["message" => "failed", "success" => false], 500);
        } catch (\Throwable $th) {
            return response()->json(["message" => "error " . $th->getCode(), "success" => false], 500);
        }
    }

    /**
     * Summary of delete_data_calon_kkn
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public static function delete_data_calon_kkn($id): JsonResponse
    {
        try {

            $calonkkn = DB::table("tbl_calon_kkn as calon")->
                join("tbl_mahasiswa as mhs", 'mhs.id_mhs', "=", "calon.id_mhs")->
                whereRaw("id_calon_kkn=?", [$id])->selectRaw("mhs.nim_mhs,calon.id_calon_kkn")->first();
            $nim = $calonkkn->nim_mhs;
            $pathfoto = 'calonkkn/foto/' . $nim;
            $pathkrs = 'calonkkn/krs_terakhir/' . $nim;
            $pathsuratizinatasan = 'calonkkn/surat_izin_atasan/' . $nim;
            $pathvaksin = "calonkkn/sertifikat_vaksin/" . $nim;
            $pathslippembayarankkn = "calonkkn/slip_pembayaran_kkn/" . $nim;
            $pathslippembayaransmt = "calonkkn/slip_pembayaran_smt/" . $nim;
            $pathsuratizinortu = "calonkkn/surat_izin_ortu/" . $nim;
            $pathtranskipnilai = "calonkkn/transkip_nilai/" . $nim;
            DB::beginTransaction();
            File::deleteDirectory($pathfoto);
            File::deleteDirectory($pathsuratizinatasan);
            File::deleteDirectory($pathkrs);
            File::deleteDirectory($pathvaksin);
            File::deleteDirectory($pathslippembayarankkn);
            File::deleteDirectory($pathslippembayaransmt);
            File::deleteDirectory($pathsuratizinortu);
            File::deleteDirectory($pathtranskipnilai);
            CalonkknModel::findOrFail($id)->delete();
            DB::table("tbl_detail_anggota_kkn")->whereRaw("id_calon_kkn=?", [$calonkkn->id_calon_kkn])->delete();
            berkasCalonKknModel::whereRaw("id_calon_kkn=?", $calonkkn->id_calon_kkn)->delete();
            DB::commit();
            return response()->json(["message" => "success", "success" => true], 200);

        } catch (ModelNotFoundException $th) {
            DB::rollBack();
            return response()->json(["message" => "Invalid id calon kkn", "success" => false], 500);
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            return response()->json(["message" => "error in server", "success" => false], 500);
        }
    }

    /**
     * Summary of detail_calon_kkn
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public static function detail_calon_kkn($id): JsonResponse
    {
        try {
            $data = CalonkknModel::where("id_mhs", $id)->first();
            return response()->json(["message" => "success", "success" => true, "data" => $data], 200);

        } catch (\Throwable $th) {
            return response()->json(["message" => "Error ", "success" => false], 500);
        }
    }

}
