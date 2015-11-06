<?php
class GalleryImage extends MyModel{
	protected $table   = "gallery_images";
	public $timestamps = false;

	public function Gallery(){
		return $this->belongsTo('Gallery');
	}

}