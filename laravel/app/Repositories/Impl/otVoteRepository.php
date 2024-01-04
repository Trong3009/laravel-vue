<?php
namespace App\Repositories\Impl;
use App\Models\OverTimeVote;
use App\Repositories\OtVoteRepoInterface;

class otVoteRepository extends BaseRepository implements OtVoteRepoInterface
{
    public function model(): string{
        return OverTimeVote::class;
    }
    public function index(array $inputs): mixed
    {
        if(!empty($inputs['per_page'])){
            $perPage = $inputs['per_page'];
        }else{
            $perPage = parent::PER_PAGE;
        }
        $conditions['per_page'] = $perPage;
        $conditions['fields'] = '*';
        $conditions['sort'] = [
            'id' => 'desc'
        ];
        if(isset($inputs['filter'])){
            $filterObject = json_decode($inputs['filter'], true);
            if(!empty($filterObject)){
                foreach($filterObject as $key => $value){
                    if($key == 'keyword'){
                        $conditions['filter']['cadition_like'] = [
                            'status' =>$value,
                            'name' => $value,
                        ];
                    }else{
                        $conditions['filter']['where_columns'][$key] = $value;
                    }
                }
            }
        }
        if(isset($inputs['sort'])){
            $conditions['sort'] = json_decode($inputs['sort'], true);
        }
        $this->filterBuilder = $this->model;
        return $this->getListIntoTable($conditions, $perPage);
    }
    public function createOT(array $inputs): array
    {
        $inputs['status'] = static::VOTE_STATUS;
        $otVote = new OverTimeVote;
        $otVote->fill($inputs)->save();
        return $otVote->toArray();
    }
    public function updateOT($id, array $inputs): array
    {
        $otVote = OverTimeVote::find($id);
        if($otVote){
            $otVote->fill($inputs)->save();
            return $otVote->toArray();
        }
        return [];
    }

    public function deleteOT($id): void
    {
        $otVote = OverTimeVote::find($id);
        if($otVote){
            $otVote->delete();
        }
    }

    public function pluckModel(): mixed
    {
        return OverTimeVote::whereNull('id')
                ->where('status', '=', static::VOTE_STATUS)
                ->get();
    }
}