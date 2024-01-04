<?php
namespace App\Repositories\Impl;

use App\Repositories\LateVoteRepoInterface;
use App\Models\LateVote;

class LateVoteRepository extends BaseRepository implements LateVoteRepoInterface
{
    public function model(): string
    {
        return LateVote::class;
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
    public function createLateVote(array $inputs): array
    {
        $inputs['status'] = static::WORKING_STATUS;
        $latevote = new LateVote;
        $latevote->fill($inputs)->save();
        
        return $latevote->toArray();
    }
    public function updateLateVote($id, array $inputs): array
    {
        $latevote = LateVote::find($id);
        if($latevote){
            $latevote->fill($inputs)->save();
            return $latevote->toArray();
        }
        return [];
    }
    public function pluckModel(): mixed
    {
        return LateVote::whereNull('id')
                ->where('status', '=', static::WORKING_STATUS)
                ->get();    
    }

    public function deleteLateVote($id): bool
    {
        $latevote = LateVote::find($id);
        if(!$latevote){
            return false; 
        }
        return $latevote->deleted();
    }

    public function updateWorkStatus($id, $status): bool
    {
        $latevote = $this->model->find($id);
        $latevote->update(['status' => $status]);

        return true;
    }
}
