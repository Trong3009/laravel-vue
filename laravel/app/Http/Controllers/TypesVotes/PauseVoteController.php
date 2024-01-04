<?php

namespace App\Http\Controllers\TypesVotes;

use App\Http\Controllers\Controller;
use App\Http\Requests\PauseVoteRequest;
use App\Repositories\PauseVoteRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PauseVoteController extends Controller
{
    protected PauseVoteRepoInterface $pauseVoteRepository;
    public function __construct(
        PauseVoteRepoInterface $pauseVoteRepository
        )
    {
        $this->pauseVoteRepository = $pauseVoteRepository;
    }    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):JsonResponse
    {
        $pauseVote = $this->pauseVoteRepository->index($request->all())->toArray();
        return $this->responseJson($pauseVote, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PauseVoteRequest $request): JsonResponse
    {
        $inputs = $request->all();

        try {
            DB::begintransaction();
            $pauseVote = $this->pauseVoteRepository->createPauseVote($inputs);
            DB::commit();

            return $this->responseJson($pauseVote, 200);
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return $this->responseJsonError(static::ERROR, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(): JsonResponse
    {
        $pauseVote = $this->pauseVoteRepository->pluckModel()->toArray();
        $pauseVote = array_map(function($item){
            return [
                'id' => $item['id'],
                'reason' => $item['reason'],
                'status' => $item['status'],
            ];
        }, $pauseVote);
        return $this->responseJson($pauseVote, 200);
    }

    /**
     * Update the specified resource in storage.
     *          catch (\Exception $e) 
        
     */
    public function update($id ,PauseVoteRequest $request): JsonResponse
    {   
        $inputs = $request->all();
        try {
            $this->pauseVoteRepository->updatePauseVote($request->validated(), $id);
            return \response()->json([
                "code" => 200,
                "message" => "Cập nhật vai trò thành công"
            ], 200);
        }

        catch(NotFoundHttpException $e){
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        catch(\Exception $e){
            return $this->responseJson($e->getMessage(), $e->getCode());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->pauseVoteRepository->deletePauseVote($id);
            return \Response()->json([
                "code" => 200,
                "message" => "Chúc mừng bạn đi làm"
            ], 200);
        }catch (NotFoundHttpException $e){
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        }catch(\Exception $e){
            return $this->responseJson($e->getMessage(),$e->getCode());
        }
    }

}
