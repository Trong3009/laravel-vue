<?php 
namespace App\Repositories\Impl;

use App\Models\Onsite;
use App\Repositories\OnsiteRepoInterface;

class OnsiteRepository extends BaseRepository implements OnsiteRepoInterface
{
    public function model(): string 
    {
        return Onsite::class;
    }
    public function index(array $inputs): mixed
    {
        if(!empty($inputs['per_page'])){
            $perPage = $inputs['per_page'];
        }else{
            $perPage = parent::PER_PAGE;
        }
        $conditions['per_page'] = $perPage;
        $conditions['fields']= '*';
        $conditions['sort'] = ['id' => 'desc'];
        if(isset($inputs['filter'])){
            $filterObject = json_decode($inputs['filter'], true);
            if(!empty($filterObject)){
                foreach($filterObject as $key => $value){
                    if($key == 'keyword'){
                        $conditions['filter']['cadition_like']= [
                            'stattus' =>$value,
                            'name' =>$value,
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
    public function createOnsite(array $inputs): array
    {
        $inputs['status'] = static::WORKING_STATUS;
        $onsite = new Onsite;
        $onsite->fill($inputs)->save();
        return $onsite->toArray();
    }
    public function updateOnsite($id, array $inputs): array
    {
        $onsite = Onsite::find($id);
        if($onsite){
            $onsite->fill($inputs)->save();
            return $onsite->toArray();
        }
        return [];
    }
    public function deleteOnsite($id): bool
    {
        $onsite = Onsite::find($id);
        if(!$onsite){
            return false; 
        }
        return $onsite->deleted();
    }
    public function pluckModel():mixed
    {
        return Onsite::whereNull('id')->where('status', '=', static::WORKING_STATUS)->get();
    }
}