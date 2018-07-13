<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description','titulo'];
    
    public function getResults($data,$total)
    {
        if (!isset($data['filter']) && !isset($data['name']) && !isset($data['description']))
            return $this->get();

        return $this->where(function ($query) use ($data) {
                    if (isset($data['filter'])) {
                        $filter = $data['filter'];
                        $query->where('name', $filter);
                        $query->orWhere('description', 'LIKE', "%{$filter}%");
                    }

                    if (isset($data['name']))
                        $query->where('name', $data['name']);
                    
                    if (isset($data['description'])) {
                        $description = $data['description'];
                        $query->where('description', 'LIKE', "%{$description}%");
                    }
                })//->toSql();dd($results); ->paginate($total)
                ->$this->get();
    }


    public function rules($id = ''){
        
       return [
            'name'          => "required|min:3|max:100|unique:products,name,{$id},id",
            'description'   => 'required|min:3|max:1500',
       ];
        
    }
    
    public function rulesSearch(){
        
        return [
            'key-search'   => 'required',
        ];
         
     }
     
     public function search($data, $totalPage){
         
        return $this->where('name', $data['key-search'])
                    ->orwhere('description', 'LIKE' , "%{$data['key-search']}%")
                    ->paginate($totalPage);
        
     }
}
