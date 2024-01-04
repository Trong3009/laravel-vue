<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    CONST SUCCESS = 'Thành công';
    CONST ERROR = 'Có lỗi xảy ra vui lòng thử lại';

    /** @var int trang thai da nghi viec */
    CONST RETIRED_STATUS = 2;

    public function responseJson($data, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    public function responseJsonError($data, $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'error' => $data,
        ], $code);
    }

    public function responseJsonErrorAuth(): JsonResponse
    {
        return response()->json([
            'code' => Response::HTTP_FORBIDDEN,
            'error' => 'Không có quyền truy cập',
        ], Response::HTTP_FORBIDDEN);
    }
}
