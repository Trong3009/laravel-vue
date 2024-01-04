<?php 
namespace App\Repositories\Impl;

use App\Models\Remote;
use App\Repositories\RemoteRepoInterface;

class RemoteRepository extends BaseRepository implements RemoteRepoInterface
{
    public function model(): string
    {
        return Remote::class;
    }
    public function index(array $inputs): mixed
    {
        if(!empty($input['per_page'])){
            $perPage = $inputs['per_page'];
        }else {
            $perPage = parent::PER_PAGE;
        }
        $conditions['perpage'] = $perPage;
        $conditions['fields'] = '*';
        $conditions['sort'] = ['id' => 'desc'];
        if (isset($inputs['filter'])){
            $filterObject = json_decode($inputs['filter'], true);
            if(!empty($filterObject)){
                foreach($filterObject as $key => $value){
                    if($key == 'keyword'){
                        $conditions['filter']['condition_like']= [
                            'name' => $value,
                            'status' => $value,
                        ];
                    }else{
                        $conditions['filter']['where_columns'][$key]= $value;
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
    public function createRemote(array $inputs): array
    {
        $inputs['status'] = static::VOTE_STATUS;
        $remoteVote = new Remote;
        $remoteVote->fill($inputs)->save();
        return $remoteVote->toArray();
    }
    public function updateRemote($id, array $inputs): array
    {
        $remoteVote = Remote::find($id);
        if($remoteVote){
            $remoteVote->fill($inputs)->save();
            return $remoteVote->toArray();
        }
        return [];
    }
    public function deleteRemote($id): void
    {
        $remoteVote = Remote::find($id);
        if($remoteVote){
            $remoteVote->delete();
        }
    }
    public function pluckModel(): mixed
    {
        return Remote::whereNull('id')->where('status', '=', static::VOTE_STATUS)->get();
    }
}