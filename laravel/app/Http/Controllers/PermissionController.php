<?php

namespace App\Http\Controllers;

use App\Repositories\PermissionRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    protected PermissionRepoInterface $permissionRepository;

    public function __construct(PermissionRepoInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function view(): JsonResponse
    {
        $permissions = $this->permissionRepository->getList();

        return $this->responseJson($permissions, 200);
    }
}
