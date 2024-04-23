<?php

namespace App\Repository\Master;

use App\Models\master\NotifikasiModel;

class NotifikasiRepo
{
    protected NotifikasiModel $notifikasiModel;

    public function __construct(NotifikasiModel $notifikasiModel)
    {
        $this->notifikasiModel = $notifikasiModel;
    }

    public function updateNotifikasi($id)
    {
        $update = $this->notifikasiModel->where("id", $id)->first();
        if ($update) {
            $update->read = 1;
            $update->save();
            $context = $update->context;

            if ($context == 1) {
                return redirect()->route("admin.pengguna");
            }
        }
    }
}
