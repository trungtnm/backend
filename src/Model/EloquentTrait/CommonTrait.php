<?php
namespace Trungtnm\Backend\Model\EloquentTrait;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

trait CommonTrait
{
    /**
     * @param $query
     * @param string $field
     * @param string $order
     * @return mixed
     */
    public function scopeLatest($query, $field = 'created_at', $order = 'desc'){
        return $query->orderBy($field, $order);
    }

    /**
     * @param $query
     * @param $slug
     * @return mixed
     */
    public function scopeSlug($query, $slug){
        return $query->where('slug', $slug);
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
        return $query->where('status', true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInActive($query){
        return $query->where('status', false);
    }

    /**
     * Get results by page
     * if data has conditions, it must be queried before call this method
     * edited from source : http://culttt.com/2014/02/24/working-pagination-laravel-4/
     * @param string $key : key cache for this list, not inlude page
     * @param int $page
     * @param int $perpage
     * @return Paginator
     */
    public function scopeByPage($query, $key, $cacheTime, $perpage = 10, $page = null)
    {
        $page = $page !== null ? $page : Input::get('p');
        $page = intval($page) < 1 ? 1 : $page;
        $key  = $key . $page . "_" . $perpage;
        $items = Cache::remember($key, $cacheTime, function() use ($perpage, $page, $query){
            return $query->skip($perpage * ($page - 1))->take($perpage)->get();
        });
        $totalItems = $query->count();
        $data       = $items->all();

        $retval     = Paginator::make($data, $totalItems, $perpage);
        return $retval;
    }

    /**
     * Delete getByPage cache
     * @param string $key : key cache for this list, not inlude page
     * @param int $totalItems
     * @param int $perpage
     * @return boolean
     */
    public function forgetByPage($key, $totalItems, $perpage = 10){
        $totalPages = ceil($totalItems / $perpage);
        for($p = 1; $p <= $totalPages; $p++){
            try{
                $k = $key . $p . "_" . $perpage;
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
     * get row by slug
     * @param $query
     * @param $slug
     * @param null $active
     * @return mixed
     */
    public function scopeGetBySlug($query, $slug, $active = null)
    {
        if (!is_null($active)) {
            $query->active($active);
        }
        return $query->slug($slug)->first();
    }
}