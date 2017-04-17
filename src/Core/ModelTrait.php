<?php

namespace Trungtnm\Backend\Core;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait ModelTrait
{
    /**
     * sub class override this method to control cache data
     */
    public function renewCache() { }

    /**
     * @param        $query
     * @param string $field
     * @param string $order
     *
     * @return mixed
     */
    public function scopeLatest($query, $field = 'updated_at', $order = 'desc')
    {
        return $query->orderBy($field, $order);
    }

    /**
     * @param $query
     * @param $slug
     *
     * @return mixed
     */
    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * @param     $query
     * @param int $id
     *
     * @return mixed
     */
    public function scopeOthers($query, $id = 0)
    {
        return $query->where('id', '!=', $id);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeInActive($query)
    {
        return $query->where('status', false);
    }

    /**
     * @param        $query
     * @param string $keyword
     * @param int    $filterBy
     *
     * @return mixed
     */
    public function scopeSearch($query, $keyword = '', $filterBy = 0)
    {
        if (!empty($filterBy)) {

            if (!is_array($filterBy) && !empty($keyword)) {
                $query->where($filterBy, 'LIKE', "%{$keyword}%");
            } else {
                foreach ($filterBy as $key => $field) {
                    if ($field['name'] == 'search_id' && !empty($field['value'])) {
                        //find by ID
                        $query->where('id', $field['value']);
                        break;
                    }

                    $searchField = "";
                    if (strpos($field['name'], 'search__') !== false) {
                        $searchField = explode_end('__', $field['name']);
                    }
                    if ($searchField) {

                        if ($searchField == 'filterBy') {
                            if (!empty($keyword)) {
                                if (strpos($field['value'], 'scope') !== false) {
                                    $scopeFunction = strtolower(substr($field['value'], 5));
                                    $query->{$scopeFunction}($keyword);
                                } elseif (strpos($field['value'], 'translated') !== false) {
                                    $translationField = strtolower(substr($field['value'], 10));
                                    $query->findTranslate($translationField, $keyword);
                                } else {
                                    $query->where($field['value'], 'like', "%{$keyword}%");
                                }
                            }
                        } elseif ($searchField == 'status') {
                            if ($field['value'] != 'all') {
                                $query->where('status', (bool) $field['value']);
                            }
                        } elseif ($searchField != 'keyword' && $field['value']) {
                            if ($field['value'] != 'all')
                                $query->where($searchField, $field['value']);
                        }

                    }

                }
            }

        }
        if (method_exists($this, 'adapterFilter')) {
            $query = $this->adapterFilter($query);
        }
        $query->relation();

        return $query;
    }

    /**
     * extend this function to query data with model relationship
     *
     * @param  [type] $query [description]
     *
     * @return mixed [type]        [description]
     */
    public function scopeRelation($query)
    {
        //$query->with('modelA','modelB');
        return $query;
    }

    /**
     * Get results by page
     * if data has conditions, it must be queried before call this method
     * edited from source : http://culttt.com/2014/02/24/working-pagination-laravel-4/
     *
     * @param string $key : key cache for this list, not inlude page
     * @param int    $page
     * @param int    $perpage
     *
     * @return Paginator
     */
    public function scopeByPage($query, $key, $cacheTime, $perpage = 10, $page = null)
    {
        $page = $page !== null ? $page : Input::get('p');
        $page = intval($page) < 1 ? 1 : $page;
        $key = $key . $page . "_" . $perpage;
        $items = Cache::remember($key, $cacheTime, function () use ($perpage, $page, $query) {
            return $query->skip($perpage * ($page - 1))->take($perpage)->get();
        });
        $totalItems = $query->count();
        $data = $items->all();

        $retval = Paginator::make($data, $totalItems, $perpage);

        return $retval;
    }

    /**
     * Delete getByPage cache
     *
     * @param string $key : key cache for this list, not inlude page
     * @param int    $totalItems
     * @param int    $perpage
     *
     * @return boolean
     */
    public function forgetByPage($key, $totalItems, $perpage = 10)
    {
        $totalPages = ceil($totalItems / $perpage);
        for ($p = 1; $p <= $totalPages; $p++) {
            try {
                $k = $key . $p . "_" . $perpage;
                Cache::forget($k);
            } catch (\Exception $e) {
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
    public function addSeoColumns()
    {
        try {
            Schema::table($this->table, function ($table) {
                $table->string('seo_description', 255)->nullable();
                $table->string('seo_keyword', 255)->nullable();
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }

        return true;
    }
}