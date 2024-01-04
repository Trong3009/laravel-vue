<?php

namespace App\Repositories\Impl;

use App\Imports\ImportExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserDetail;
use App\Repositories\UserDetailRepoInterface;
use Illuminate\Support\Facades\DB;


class UserDetailRepository extends BaseRepository implements UserDetailRepoInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return UserDetail::class;
    }

    /**
     * @param array $inputs
     * @return mixed
     */
    public function getList(array $inputs): mixed
    {
        if (!empty($inputs['per_page'])) {
            $perPage = $inputs['per_page'];
        } else {
            $perPage = parent::PER_PAGE;
        }
        $conditions['per_page'] = $perPage;
        $conditions['fields'] = '*';

        $conditions['sort'] = [
            'id' => 'desc'
        ];

        // tìm kiếm like trên input name="keyword"
        if (isset($inputs['filter'])) {
            $filterObject = json_decode($inputs['filter'], true);
            if (!empty($filterObject)) {
                foreach ($filterObject as $key => $value) {
                    if ($key == 'keyword') {
                        $conditions['filter']['condition_like'] = [
                            'code' => $value,
                            'name' => $value,
                            'phone' => $value,
                            'email' => $value,
                        ];
                    } else {
                        $conditions['filter']['where_columns'][$key] = $value;
                    }
                }
            }
        }
        // sắp xếp
        if (isset($inputs['sort'])) {
            $conditions['sort'] = json_decode($inputs['sort'], true);
        }

        $this->filterBuilder = $this->model;

        return $this->getListIntoTable($conditions, $perPage);
    }

    /**
     * @param array $inputs
     * @return array
     */
    public function createUserDetail(array $inputs): array
    {
        $inputs['work_status'] = static::WORKING_STATUS;
        $userDetail = new UserDetail;
        $userDetail->fill($inputs)->save();

        return $userDetail->toArray();
    }

    /**
     * @param $id
     * @param array $inputs
     * @return array
     */
    public function updateUserDetail($id, array $inputs): array
    {
        $userDetail = UserDetail::find($id);
        if ($userDetail) {
            $userDetail->fill($inputs)->save();

            return $userDetail->toArray();
        }
        return [];
    }

    public function pluckModel(): mixed
    {
        return UserDetail::whereNull('user_id')
                ->where('work_status', '=', static::WORKING_STATUS)
                ->get();
    }

    /**
     * @param $id
     * @param $status
     * @return bool
     */
    public function updateWorkStatus($id, $status): bool
    {
        $userDetail = $this->model->find($id);
        $userDetail->update(['work_status' => $status]);

        return true;
    }

    /**
     * @param $id
     * @return void
     */
    public function deleteByUser($id): void
    {
        $userDetail = UserDetail::where('user_id', $id);
        if ($userDetail) {
            $userDetail->update(['user_id' => null]);
        }
    }


    /**
     * @throws \Exception
     */
    public function createMultipleUserDetail($request): string
    {
        $idsCreate = 0;
        $idsUpdate = 0;
        try {
            DB::beginTransaction();
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $import = new ImportExcel([]);
                Excel::import($import, $file);
                $dataInputs = $import->getData();
                $dataInputs = $this->formatData($dataInputs);
                if (count($dataInputs) === 0) {
                    throw new \Exception("Dữ liệu không hợp lệ");
                }
                foreach ($dataInputs as $dataInput) {
                    $userDetail = UserDetail::where('code', $dataInput['code'])->first();
                    if (!empty($userDetail)) {
                        $userDetail->update($dataInput);
                        $idsUpdate += 1;
                    } else {
                        UserDetail::create($dataInput);
                        $idsCreate += 1;
                    }
                }
            }
            DB::commit();

            return "Tạo mới $idsCreate hồ sơ và cập nhật thông tin cho $idsUpdate hồ sơ";
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            throw $e;
        }
    }

    public function formatData($inputs)
    {
        foreach ($inputs as &$item) {
            foreach ($item as $key => $value) {
                if ($key == 'birthday' && !empty($value)) {
                    $item['birthday'] = Carbon::createFromFormat('d/m/Y', $item['birthday'])->format('Y-m-d');
                }
                // ngày thử việc
                if ($key == 'probation_day' && !empty($value)) {
                    $item['probation_day'] = Carbon::createFromFormat('d/m/Y', $item['birthday'])->format('Y-m-d');
                }
                // ngày chính thức
                if ($key == 'official_day' && !empty($value)) {
                    $item['official_day'] = Carbon::createFromFormat('d/m/Y', $item['birthday'])->format('Y-m-d');
                }
                // ngay nghi viec
                if ($key == 'quit_date' && !empty($value)) {
                    $item['quit_date'] = Carbon::createFromFormat('d/m/Y', $item['birthday'])->format('Y-m-d');
                }
                //ngay cap cccd ho chieu
                if ($key == 'date_identity_card' && !empty($value)) {
                    $item['date_identity_card'] = Carbon::createFromFormat('d/m/Y', $item['birthday'])->format('Y-m-d');
                }
                //ngay ket nap Dang
                if ($key == 'date_communist' && !empty($value)) {
                    $item['date_communist'] = Carbon::createFromFormat('d/m/Y', $item['birthday'])->format('Y-m-d');
                }
            }
        }

        return $inputs;
    }
}
