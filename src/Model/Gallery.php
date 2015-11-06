<?php
class Gallery extends MyModel{
	protected $table = "gallery";

	protected $appends = ['link'];

	public $seoFields = true;

	public function getLinkAttribute(){
		return route('galleryDetail', $this->slug);
	}

	public function GalleryImage(){
		return $this->hasMany('GalleryImage')->remember(CACHE_1DAY);
	}

	public function scopeRelation($query){
		return $query->with('GalleryImage');
	}

	public function getHomeGalleries($limit = 6, $cache = CACHE_1DAY){
		return 
		$this
		->active()
		->orderBy('id', 'desc')
		->take($limit)
		->relation()
		->remember($cache)
		->get();
	}

	public function getLatest($perpage = 20, $cache = CACHE_1DAY){
		return 
		$this
		->active()
		->orderBy('id', 'desc')
		->relation()
		->remember($cache)
		->paginate($perpage);
	}

	public function getBySlug($slug, $cache = CACHE_1DAY){
		return 
		$this
		->slug($slug)
		->active()
		->remember($cache)
		->first();
	}

	public function getOthers($limit = 4, $cache = CACHE_1DAY){
		return 
		$this
		->active()
		->others($this->id)
		->relation()
		->take($limit)
		->remember($cache)
		->get();
	}
}