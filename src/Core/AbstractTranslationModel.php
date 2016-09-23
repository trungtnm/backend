<?php
namespace Trungtnm\Backend\Core;

abstract class AbstractTranslationModel extends \Eloquent {
    public $timestamps = false;
    /**
     * @param string $slug
     * @return $this
     */
    public function setSlugAttribute($slug = '')
    {
        if (empty($slug)) {
            $this->attributes['slug'] = str_slug($this->title);
        } else {
            $this->attributes['slug'] = $slug;
        }
        return $this;
    }
}