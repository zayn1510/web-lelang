<?php

namespace App\Http\Controllers\api\master;

use App\Http\Controllers\Controller;
use App\Repository\Master\NotifikasiRepo;

class NotifikasiController extends Controller
{
    protected NotifikasiRepo $notifikasiRepo;
    public function __construct(NotifikasiRepo $notifikasiRepo)
    {
        $this->notifikasiRepo = $notifikasiRepo;
    }

    public function update_notifikasi($id)
    {
        return $this->notifikasiRepo->updateNotifikasi($id);
    }
}
