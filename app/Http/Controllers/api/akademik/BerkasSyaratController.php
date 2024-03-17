<?php

namespace App\Http\Controllers\api\akademik;

use App\Http\Controllers\Controller;
use App\Http\Requests\akademik\SyaratBerkasRequest;
use App\Repository\Akademik\SyaratBerkasRepository;
use Illuminate\Http\JsonResponse;

class BerkasSyaratController extends Controller
{
    protected SyaratBerkasRepository $syaratBerkasRepository;

    public function __construct(SyaratBerkasRepository $syaratBerkasRepository)
    {
        $this->syaratBerkasRepository = $syaratBerkasRepository;
    }

    public function get_data(): JsonResponse
    {
        return $this->syaratBerkasRepository->loadDadta();
    }

    public function save_data(SyaratBerkasRequest $syaratBerkasRequest): JsonResponse
    {
        return $this->syaratBerkasRepository->createData($syaratBerkasRequest);
    }

    public function update_data(SyaratBerkasRequest $syaratBerkasRequest, int $id): JsonResponse
    {
        return $this->syaratBerkasRepository->updateData($syaratBerkasRequest, $id);
    }

    public function delete_data(int $id): JsonResponse
    {
        return $this->syaratBerkasRepository->deleteData($id);
    }

    public function get_data_berkas_user($idcalonkkn):JsonResponse
    {
        return $this->syaratBerkasRepository->loadDataUser($idcalonkkn);
    }

}
