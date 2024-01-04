<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDetailRequest;
use App\Models\UserDetail;
use App\Repositories\UserDetailRepoInterface;
use App\Traits\GetDataExportExcel;
use App\Traits\uploadFIle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserDetailController extends Controller
{
    use uploadFIle, GetDataExportExcel;

    protected UserDetailRepoInterface $userDetailRepository;

    public function __construct(
        UserDetailRepoInterface $userDetailRepository
    )
    {
        $this->userDetailRepository = $userDetailRepository;
    }

    public function getList(Request $request): JsonResponse
    {
        $userDetails = $this->userDetailRepository->getList($request->all())->toArray();

        return $this->responseJson($userDetails, 200);
    }

    /**
     * @throws \Exception
     */
    public function create(UserDetailRequest $request): JsonResponse
    {
        $inputs = $request->all();
        if (!empty($inputs['avatar'])) {
            $avatarPath = $this->uploadFileTraits($inputs['avatar']);
            $inputs['avatar'] = config('define.DOMAIN_API') . $avatarPath;
        }
        try {
            DB::begintransaction();
            $userDetail = $this->userDetailRepository->createUserDetail($inputs);
            DB::commit();

            return $this->responseJson($userDetail, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    /**
     * @throws \Exception
     */
    public function update($id, UserDetailRequest $request): JsonResponse
    {
        $dataUserDetailOld = $this->userDetailRepository->getById($id);
        $oldFileAvt = $dataUserDetailOld->avatar;
        $inputs = $request->all();
        if (!empty($inputs['avatar'])) {
            $avatarPath = $this->uploadFileTraits($inputs['avatar']);
            unset($inputs['avatar']);
        }
        try {
            DB::beginTransaction();
            if (!empty($avatarPath)) {
                if (!empty($oldFileAvt)) {
                    $oldFileAvt = str_replace(config('define.DOMAIN_API'), "", $oldFileAvt);
                    if (file_exists($oldFileAvt)) {
                        unlink($oldFileAvt);
                    }
                }
                $inputs['avatar'] = config('define.DOMAIN_API') . $avatarPath;
            }
            $userDetail = $this->userDetailRepository->updateUserDetail($id, $inputs);
            DB::commit();

            return $this->responseJson($userDetail, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->userDetailRepository->updateWorkStatus($id, static::RETIRED_STATUS);
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
        $userDetails = $this->userDetailRepository->pluckModel()->toArray();

        $userDetails = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'code' => $item['code'],
                'email' => $item['email'],
                'name' => $item['name'],
                'phone' => $item['phone'],
                'department_id' => $item['department_id'],
                'work_status' => $item['work_status'],
            ];
        }, $userDetails);

        return $this->responseJson($userDetails, 200);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;
        $result = $this->userDetailRepository->delete($ids);
        if ($result) {
            return $this->responseJson('Xóa thành công ' . $result . ' record', 200);
        }

        return $this->responseJsonError(static::ERROR, 500);
    }

    public function createByFile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx|max:10000000',
        ]);
        if ($validator->fails()) {
            return $this->responseJson($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $data = $this->userDetailRepository->createMultipleUserDetail($request);

            return $this->responseJson($data, 200);
        } catch (\Exception $e) {
            return $this->responseJson($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDataExportExcel(Request $request): JsonResponse
    {
        $userDetails = $this->userDetailRepository->getList($request->all());
        $data = $this->getDataExcel($userDetails);

        return $this->responseJson($data, 200);
    }
}
