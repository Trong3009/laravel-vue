<?php 
namespace App\Repositories\Impl;

use App\Repositories\PauseVoteRepoInterface;
use App\Models\PauseVote;
use Illuminate\Support\Facades\DB;

class PauseVoteRepository extends BaseRepository implements PauseVoteRepoInterface
{
    public function model(): string
    {
        return PauseVote::class;
    }
    public function index(array $inputs): mixed
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
    public function createPauseVote(array $inputs): array
    {
        $inputs['status'] = static::VOTE_STATUS;
        $pausevote = new PauseVote;
        $pausevote->fill($inputs)->save();

        return $pausevote->toArray();
    }
    public function updatePauseVote( $id, array $inputs): array
    {
        $pausevote = PauseVote::find($id);
        if ($pausevote) {
            $pausevote->fill($inputs)->save();

            return $pausevote->toArray();
        }
        return [];
    }

    public function pluckModel(): mixed
    {
        return PauseVote::whereNull('id')
                ->where('status', '=', static::VOTE_STATUS)
                ->get();
    }
    public function deletePauseVote($id): void
    {
        $pausevote = PauseVote::find($id);
        if(!$pausevote){
            $pausevote->delete();
        }
        
    }
} 