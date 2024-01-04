<?php

namespace App\Http\Controllers\TypesVotes;

use App\Http\Controllers\Controller;
use App\Http\Requests\OnsiteRequest;
use App\Repositories\OnsiteRepoInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OnsiteController extends Controller
{
    protected OnsiteRepoInterface $onsiteRepository;
    public function __construct(OnsiteRepoInterface $onsiteRepository)
    {
        $this->onsiteRepository = $onsiteRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $onsite = $this->onsiteRepository->index($request->all())->toArray();
        return $this->responseJson($onsite, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OnsiteRequest $request):JsonResponse
    {
        $inputs = $request->all();
        try {
            DB::beginTransaction();
            $onsite = $this->onsiteRepository->createOnsite($inputs);
            DB::commit();
            return $this->responseJson($onsite, 200);
        }catch(\Exception $e){
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
        try{
            DB::beginTransaction();
            $onsite = $this->onsiteRepository->updateOnsite($id, $inputs);
            DB::commit();
            return $this->responseJson($onsite, 200);
        }
        catch (\Exception $e) {
                DB::rollBack();
                Log::info($e->getMessage());
    
                return $this->responseJsonError(static::ERROR, 500);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id): JsonResponse
    {
        try{
            $this->onsiteRepository->deleteOnsite($id);
            return \Response()->json([
                "code" => 200,
                "message" => "Chúc mừng bạn vẫn đến công ty"
            ], 200);
        }catch(NotFoundHttpException $e){
            return $this->responseJson($e->getMessage(), Response::HTTP_NOT_FOUND);
        }catch(\Exception $e){
            return $this->responseJson($e->getMessage(), $e->getCode());
        }
    }
}
