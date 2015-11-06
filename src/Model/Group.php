<?php

class Group extends Cartalyst\Sentry\Groups\Eloquent\Group {
	protected $table = 'backend_groups';
	public $showAddButton = true;
	public function getShowField(){
		return array(
			'name'         =>  array(
	            'label'         =>  "Tên group",
	            'type'          =>  'text'
	        ),
	        'status'         =>  array(
	            'label'         =>  'Trạng thái',
	            'type'          =>  'boolean'
	        ),
	        'created_at'         =>  array(
	            'label'         =>  "Ngày tạo",
	            'type'          =>  'date',
	        ),
		);
	} 

	public function getSearchField(){ 
		return array(
			'name'		=>	trans('user-group::field.name'),
		);
	}

	public function scopeSearch($query, $keyword = '', $filterBy = 0)
	{	
		if( !empty($filterBy)){

			if( !is_array($filterBy) && !empty($keyword) ){
				$query->where($filterBy, 'LIKE', "%{$keyword}%");
			}else{
				foreach( $filterBy as $key => $field){
					$searchField = "";
					if(strpos($field['name'], 'search_') !== FALSE){
						$searchField = explode_end('_',$field['name']);
					}

					if($searchField){

						if($searchField == 'filterBy'){
							if(!empty($keyword)){
								if(strpos($field['value'], 'scope') !== FALSE){
									$scopeFunction = substr($field['value'], 5);
									$query->{$scopeFunction}($keyword);
								}else{
									$query->orWhere($field['value'], 'like', "%{$keyword}%");
								}
							}
						}
						elseif($searchField == 'status'){
							if($field['value'] != 'all'){
								$query->where('status', (bool) $field['value']);
							}
						}
						elseif($searchField != 'keyword' && $field['value']){
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
}