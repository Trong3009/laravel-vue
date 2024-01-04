<?php

namespace App\Http\Controllers\TypesVotes;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RemoteRequest;
use App\Repositories\RemoteRepoInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RemoteController extends Controller
{
    protected RemoteRepoInterface $remoteRepository;
    public function __construct(RemoteRepoInterface $remoteRepository)
    {
        $this->remoteRepository = $remoteRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $remote = $this->remoteRepository->index($request->all())->toArray();
        return $this->responseJson($remote, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RemoteRequest $request): JsonResponse
    {
        $inputs = $request->all();
        try {
            DB::begintransaction();
            $remote = $this->remoteRepository->createRemote($inputs);
            DB::commit();
            return $this->responseJson($remote, 200);
        } catch(\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $inputs = $request->all();
        try {
            DB::beginTransaction();
            $remote = $this->remoteRepository->updateRemote($id, $inputs);
            DB::commit();
            return $this->responseJson($remote, 200);
        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->remoteRepository->deleteRemote($id);

            return \response()->json([
                "code" => 200,
                "message" => "Xóa remote thành công"
            ], 200);
        } catch (NotFoundHttpException $e) {
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->responseJson($e->getMessage(), $e->getCode());
        }
    }
}
