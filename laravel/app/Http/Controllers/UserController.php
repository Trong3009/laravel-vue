<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\UserDetailRepoInterface;
use App\Repositories\UserRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\HasPermissions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    use HasPermissions;
    protected UserRepoInterface $userRepository;
    protected UserDetailRepoInterface $userDetailRepository;

    public function __construct(
        UserRepoInterface $userRepository,
        UserDetailRepoInterface $userDetailRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->userDetailRepository = $userDetailRepository;
    }

    public function getList(Request $request): JsonResponse
    {
        if (!$this->hasPermission('user-list')) {
            return $this->responseJsonErrorAuth();
        }
        $users = $this->userRepository->getList($request->all())->toArray();

        return $this->responseJson($users, 200);
    }

    public function create(UserRequest $request): JsonResponse
    {
        if (!$this->hasPermission('user-create')) {
            return $this->responseJsonErrorAuth();
        }
        $inputs = $request->all();
        try {
            DB::begintransaction();
            $user = $this->userRepository->createUser($inputs);
            $this->userDetailRepository->update($inputs['user_detail_id'], ['user_id' => $user->id]);
            DB::commit();

            return $this->responseJson($user, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    public function delete($id): JsonResponse
    {
        if (!$this->hasPermission('user-delete')) {
            return $this->responseJsonErrorAuth();
        }
        try {
            DB::beginTransaction();
            $this->userRepository->destroy($id);
            $this->userDetailRepository->deleteByUser($id);
            DB::commit();

            return $this->responseJson(static::SUCCESS, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    public function getAll(): JsonResponse
    {
        if (!$this->hasPermission('user-list')) {
            return $this->responseJsonErrorAuth();
        };

        $users = $this->userRepository->pluckModel()->toArray();
        $users = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['user_details']['name'],
            ];
        }, $users);

        return $this->responseJson($users, 200);
    }

    public function update($id, UserRequest $request): JsonResponse
    {
        if (!$this->hasPermission('user-edit')) {
            return $this->responseJsonErrorAuth();
        }
        $inputs['user_name'] = $request->get('user_name');
        $inputs['parent_id'] = $request->get('parent_id');
        $inputs['role_id'] = $request->get('role_id');
        if (!empty($request->get('password'))) {
            $inputs['password'] = Hash::make($request->get('password'));
        }
        try {
            DB::beginTransaction();
            $this->userRepository->updateUser($id, $inputs);
            DB::commit();

            return $this->responseJson(static::SUCCESS, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;
        $result = $this->userRepository->delete($ids);
        if ($result) {
            return $this->responseJson('Xóa thành công '. $result . ' record', 200);
        }

        return $this->responseJsonError(static::ERROR, 500);
    }

    public function lockAndUnlock($id, Request $request): JsonResponse
    {
        try {
            $this->userRepository->lockAndUnlock($id, $request->all());

            return $this->responseJson(static::SUCCESS, 200);
        } catch (NotFoundHttpException $e) {
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->responseJsonError($e->getMessage(), 500);
        }
    }
}
