<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Repositories\PermissionRepoInterface;
use App\Repositories\RoleRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleController extends Controller
{
    protected PermissionRepoInterface $permissionRepository;
    protected RoleRepoInterface $roleRepository;

    public function __construct(
        RoleRepoInterface $roleRepository,
        PermissionRepoInterface $permissionRepository
    )
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }


    public function view(Request $request): JsonResponse
    {
        $roles = $this->roleRepository->getList($request->all())->toArray();

        return $this->responseJson($roles, 200);
    }

    public function getAll(): JsonResponse
    {
        $roles = $this->roleRepository->getAllRoles();

        return $this->responseJson($roles, 200);
    }


    public function create(RoleRequest $request): JsonResponse
    {
        $role = $this->roleRepository->createRole($request->all());

        return $this->responseJson($role, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $role = $this->roleRepository->getDetail($id);
            $permissionsAll = $this->permissionRepository->getList();

            return $this->responseJson([
                'code' => 200,
                'role' => $role,
                'permissions' => $permissionsAll
            ], 200);
        } catch (NotFoundHttpException $e) {
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update(RoleRequest $request, $id): JsonResponse
    {
        try {
            $this->roleRepository->updateRole($request->all(), $id);

            return $this->responseJson('Cập nhật vai trò thành công', 200);
        } catch (NotFoundHttpException $e) {
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->responseJson($e->getMessage(), $e->getCode());
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $this->roleRepository->deleteRole($id);

            return \response()->json([
                "code" => 200,
                "message" => "Xóa vai trò thành công"
            ], 200);
        } catch (NotFoundHttpException $e) {
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->responseJson($e->getMessage(), $e->getCode());
        }
    }


    public function restore()
    {
        //
    }


    public function forceDelete()
    {
        //
    }
}
