<?php

namespace App\Models\mahasiswa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class berkasCalonKknModel extends Model
{
    use HasFactory;
    protected $table="tbl_berkas_calon_kkn";

    protected $primaryKey="id_berkas_calon_kkn";
    public $timestamps=false;
    protected $fillable=["id_berkas_calon_kkn","id_syarat_berkas","file","id_calon_kkn"];
}
