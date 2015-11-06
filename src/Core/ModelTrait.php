<?php
namespace Trungtnm\Backend\Core;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait ModelTrait{
    /**
     * sub class override this method to control cache data
     */
    public function renewCache(){}

    /**
     * @param $query
     * @param string $field
     * @return mixed
     */
    public function scopeLatest($query, $field = 'created_at'){
        return $query->orderBy($field, 'desc');
    }

    /**
     * @param $query
     * @param $slug
     * @return mixed
     */
    public function scopeSlug($query, $slug){
        return $query->where('slug',$slug);
    }

    /**
     * @param $query
     * @param int $id
     * @return mixed
     */
    public function scopeOthers($query, $id = 0){
        return $query->where('id' , '!=', $id);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query){
        return $query->where('status',1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInActive($query){
        return $query->where('status',0);
    }

    /**
     * @param $query
     * @param string $keyword
     * @param int $filterBy
     * @return mixed
     */
    public function scopeSearch($query, $keyword = '', $filterBy = 0)
    {
        if( !empty($filterBy)){

            if( !is_array($filterBy) && !empty($keyword) ){
                $query->where($filterBy, 'LIKE', "%{$keyword}%");
            }else{
                foreach( $filterBy as $key => $field){
                    $searchField = "";
                    if(strpos($field['name'], 'search__') !== FALSE){
                        $searchField = explode_end('__',$field['name']);
                    }

                    if($searchField){

                        if($searchField == 'filterBy'){
                            if(!empty($keyword)){
                                if(strpos($field['value'], 'scope') !== FALSE){
                                    $scopeFunction = substr($field['value'], 5);
                                    $query->{$scopeFunction}($keyword);
                                }else{
                                    $query->where($field['value'], 'like', "%{$keyword}%");
                                }
                            }
                        }
                        elseif($searchField == 'status'){
                            if($field['value'] != 'all'){
                                $query->where('status', (bool) $field['value']);
                            }
                        }
                        elseif($searchField != 'keyword' && $field['value']){
                            if($field['value'] != 'all')
                                $query->where($searchField, $field['value']);
                        }

                    }

                }
            }

        }
        $query->relation();
        return $query;
    }
    /**
     * extend this function to query data with model relationship
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeRelation($query){
        //$query->with('modelA','modelB');
        return $query;
    }

    /**
     * @return array
     */
    public function getShowField(){
        return $this->showField;
    }
    /**
     * fill data to model before saving
     * @param  [array] $data model data to be saved into database
     * @return new object model with updated data
     */
    public function fillData($data){
        if(is_array($data)){
            $that = clone $this;
            if(!empty($data['id']) && $data['id'] > 0){
                $that = $this->find($data['id']);
            }
            foreach ($data as $field => $value) {
                $that->$field = $value;
            }
        }

        return $that;

    }

    /**
     * Get results by page
     * if data has conditions, it must be queried before call this method
     * edited from source : http://culttt.com/2014/02/24/working-pagination-laravel-4/
     * @param string $key : key cache for this list, not inlude page
     * @param int $page
     * @param int $perPage
     * @return Paginator
     */
    public function scopeByPage($query, $key, $cacheTime, $perPage = 10, $page = null)
    {
        $page = $page !== null ? $page : request('p');
        $page = intval($page) < 1 ? 1 : $page;
        $key  = $key . $page . "_" . $perPage;
        $items = Cache::remember($key, $cacheTime, function() use ($perPage, $page, $query){
            return $query->skip($perPage * ($page - 1))->take($perPage)->get();
        });
        $totalItems = $query->count();
        $data       = $items->all();

        $retval     = Paginator::make($data, $totalItems, $perPage);
        return $retval;
    }

    /**
     * Delete getByPage cache
     * @param string $key : key cache for this list, not inlude page
     * @param int $totalItems
     * @param int $perPage
     * @return boolean
     */
    public function forgetByPage($key, $totalItems, $perPage = 10){
        $totalPages = ceil($totalItems / $perPage);
        for($p = 1; $p <= $totalPages; $p++){
            try{
                $k = $key . $p . "_" . $perPage;
                Cache::forget($k);
            }
            catch(\Exception $e){
                // TO DO : log some information here
                return false;
            }
        }
        return true;
    }

    /**
     * Add SEO fields to database table
     *
     * @return bool
     */
    public function addSeoColumns(){
        try{
            Schema::table($this->table, function($table){
                $table->string('seo_description', 255)->nullable();
                $table->string('seo_keyword', 255)->nullable();
            });
        }
        catch(\Illuminate\Database\QueryException $e){
            return false;
        }

        return true;
    }
}