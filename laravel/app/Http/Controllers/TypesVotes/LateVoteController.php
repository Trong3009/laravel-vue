<?php

namespace App\Http\Controllers\TypesVotes;

use App\Http\Controllers\Controller;
use App\Http\Requests\LateVoteRequest;
use App\Repositories\LateVoteRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LateVoteController extends Controller
{
    protected LateVoteRepoInterface $lateVoteRepository;
    public function __construct(LateVoteRepoInterface $lateVoteRepository)
    {
        $this->lateVoteRepository = $lateVoteRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $lateVote = $this->lateVoteRepository->index($request->all())->toArray();
        return $this->responseJson($lateVote, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LateVoteRequest $request): JsonResponse
    {
        $inputs = $request->all();
        try{
            DB::begintransaction();
            $lateVote = $this->lateVoteRepository->createLateVote($inputs);
            DB::commit();
            return $this->responseJson($lateVote, 200);
        } catch(\Exception $e){
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
    public function update($id, Request $request):JsonResponse
    {   
        $inputs = $request->all();
        try{
            DB::beginTransaction();
            $lateVote = $this->lateVoteRepository->updateLateVote($id, $inputs);
            DB::commit();
            return $this->responseJson($lateVote, 200);
        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id):JsonResponse
    {
        try {
            $this->lateVoteRepository->deleteLateVote($id);
            return \Response()->json([
                "code" => 200,
                "message" => "Chúc mừng bạn không đi muộn nữa"
            ], 200);
        }catch (NotFoundHttpException $e){
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        }catch(\Exception $e){
            return $this->responseJson($e->getMessage(),$e->getCode());
        }
    }
}
