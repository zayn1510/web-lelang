<?php

namespace App\Models\akademik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasSyaratModel extends Model
{
    use HasFactory;
    protected $table = "tbl_syarat_berkas_kkn"; // table name

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        "name_berkas", "title_berkas", "tipe_berkas", "periode_kkn",
    ];
}
