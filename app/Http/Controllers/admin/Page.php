<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Page extends Controller
{

    public function pageLogin()
    {
        $message = "Selamat Datang Admin";
        return view("login", compact("message"));
    }

    public static function waktuLalu($waktu_diberikan)
    {
        $waktu_sekarang = time();

        $selisih_detik = $waktu_sekarang - strtotime($waktu_diberikan);

        $selisih_tahun = floor($selisih_detik / (60 * 60 * 24 * 365));
        $selisih_bulan = floor($selisih_detik / (60 * 60 * 24 * 30));
        $selisih_hari = floor($selisih_detik / (60 * 60 * 24));
        $selisih_jam = floor($selisih_detik / (60 * 60));

        if ($selisih_tahun > 0) {
            return $selisih_tahun . ' tahun yang lalu';
        } elseif ($selisih_bulan > 0) {
            return $selisih_bulan . ' bulan yang lalu';
        } elseif ($selisih_hari > 0) {
            return $selisih_hari . ' hari yang lalu';
        } elseif ($selisih_jam > 0) {
            return $selisih_jam . ' jam yang lalu';
        } else {
            return 'Baru saja';
        }
    }

    private static function getResultData($ket)
    {
        $data = (object) [
            "keterangan" => $ket,
            "name_aplikasi" => Str::upper("Aplikasi Sistem Informasi KKN Di Unidayan"),
        ];

        // load jumlah calon kkn
        $data->calonkkn = DB::table("tbl_calon_kkn as calon")
            ->join("tbl_periode_kkn as periode", "periode.id_periode_kkn", "=", "calon.id_periode_kkn")
            ->whereRaw("periode.status=?", [1])->selectRaw("COUNT(calon.id_calon_kkn) as jumlah")->get();

        // load jumlah dosen pembimbing lapangan
        $data->dpl = DB::table("tbl_dpl as dpl")
            ->join("tbl_periode_kkn as periode", "periode.id_periode_kkn", "=", "dpl.id_periode_kkn")
            ->whereRaw("periode.status=?", [1])->selectRaw("COUNT(dpl.id_dpl) as jumlah")->get();

        // load jumlah desa
        $data->desa = DB::table("tbl_desa as desa")
            ->join("tbl_periode_kkn as periode", "periode.id_periode_kkn", "=", "desa.id_periode_kkn")
            ->whereRaw("periode.status=?", [1])->selectRaw("COUNT(desa.id_desa) as jumlah")->get();

        $data->mahasiswa = DB::table("tbl_mahasiswa")->count();

        $data->pengguna = DB::table("users")->count();
        $data->berita = DB::table("tbl_berita")->count();

        // data calon peserta kkn
        $data->grup = DB::table("tbl_group_anggota_kkn as grup")
            ->join("tbl_dpl as dpl", "dpl.id_dpl", "=", "grup.id_dpl")
            ->join("tbl_desa as desa", "desa.id_desa", "=", "grup.id_desa")
            ->join("tbl_detail_anggota_kkn as detail", "detail.id_group", "=", "grup.id")
            ->join("tbl_periode_kkn as periode", "periode.id_periode_kkn", "=", "dpl.id_periode_kkn")
            ->selectRaw("grup.id,grup.id_dpl,grup.id_desa,COUNT(detail.id_calon_kkn) as jumlah,dpl.nama_dosen,dpl.gelar_depan,dpl.gelar_belakang,desa.desa")
            ->groupByRaw("grup.id,grup.id_dpl,grup.id_desa")->whereRaw("periode.status=?", [1])->get();

        // data periode
        $data->periode = DB::table("tbl_periode_kkn as periode")->selectRaw("periode.tahun_akademik,periode.angkatan")
            ->whereRaw("periode.status=?", [1])->first();
        $notifikasi = DB::table("tbl_notifikasi")->where('read', 0);
        $data->notifikasi = $notifikasi->count();
        $data->datanotifikasi = $notifikasi->get();
        $datanotifikasi = [];
        foreach ($notifikasi->get() as $key => $value) {
            $context = $value->context;
            $value->waktu = self::waktuLalu($value->updated_at);
            if ($context == 1) {
                $value->url = getenv("URL_APP") . "admin/update-notifikasi/" . $value->id;
            }
            $datanotifikasi[] = $value;
        }
        $data->datanotifikasi = $datanotifikasi;
        $datalogin = new \stdClass();
        $datalogin->id_pengguna = Auth::user()->id_pengguna;
        $datalogin->name = Auth::user()->username;
        $datalogin->foto = "default.jpg";
        return [$data, $datalogin];
    }
    public function pageHome()
    {
        [$data, $datalogin] = self::getResultData("Data Dashboard");
        return view("admin.home", compact("data", "datalogin"));
    }
    public function pageFakultas()
    {
        [$data, $datalogin] = self::getResultData("Data Fakultas");
        return view("admin.fakultas", compact("data", "datalogin"));
    }

    public function pageJurusan()
    {
        [$data, $datalogin] = self::getResultData("Data Jurusan");
        return view("admin.jurusan", compact("data", "datalogin"));
    }

    public function pageMahasiswa()
    {
        [$data, $datalogin] = self::getResultData("Data Mahasiswa");
        return view("admin.mahasiswa", compact("data", "datalogin"));
    }

    public function pageBerita()
    {
        [$data, $datalogin] = self::getResultData("Data Berita");
        return view("admin.berita", compact("data", "datalogin"));
    }

    public function pagePeriodeKkn()
    {
        [$data, $datalogin] = self::getResultData("Data Periode KKN");
        return view("admin.periode_kkn", compact("data", "datalogin"));
    }

    // hanlde page kkn

    public function pageCalonKkn()
    {
        [$data, $datalogin] = self::getResultData("Data Calon Peserta KKN");
        return view("admin.kkn.calon_peserta", compact("data", "datalogin"));
    }

    public function pageDesaKkn()
    {
        [$data, $datalogin] = self::getResultData("Data Desa KKN");
        return view("admin.kkn.desa_kkn", compact("data", "datalogin"));
    }

    public function pageDosenKkn()
    {
        [$data, $datalogin] = self::getResultData("Data Dosen Pembimbing Lapangan");
        return view("admin.kkn.dpl_kkn", compact("data", "datalogin"));
    }

    public function pageGroupKkn()
    {
        [$data, $datalogin] = self::getResultData("Data Group Anggota KKN");
        return view("admin.kkn.group_kkn", compact("data", "datalogin"));
    }

    public function pagePengguna()
    {
        [$data, $datalogin] = self::getResultData("Data Pengguna");
        return view("admin.pengguna", compact("data", "datalogin"));
    }

    public function pageSyarat()
    {
        [$data, $datalogin] = self::getResultData("Data Syarat Berkas KKN");
        return view("admin.syarat_berkas", compact("data", "datalogin"));
    }
}
