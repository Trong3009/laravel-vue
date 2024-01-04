<?php

namespace App\Http\Controllers\TypesVotes;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\OverTimeRequest;
use App\Repositories\otVoteRepoInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OverTimeController extends Controller
{
    protected otVoteRepoInterface $otVoteRepository;
    public function __construct(otVoteRepoInterface $otVoteRepository)
    {
        $this->otVoteRepository = $otVoteRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $otVotes = $this->otVoteRepository->index($request->all())->toArray();
        return $this->responseJson($otVotes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OverTimeRequest $request): JsonResponse
    {
        $inputs = $request->all();
        try{
            DB::beginTransaction();
            $otVotes = $this->otVoteRepository->createOT($inputs);
            DB::commit();
            return $this->responseJson($otVotes, 200);
        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->responseJson(static::ERROR, 500);
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
    public function update(OverTimeRequest $request,$id): JsonResponse
    {
        $inputs = $request->all();
        try{
            DB::beginTransaction();
            $otVotes = $this->otVoteRepository->updateOT($id, $inputs);
            DB::commit();
            return $this->responseJson($otVotes, 200);
        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->responseJson(static::ERROR, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->otVoteRepository->deleteOT($id);

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
}
