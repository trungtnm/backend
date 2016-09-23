<?php
namespace Trungtnm\Backend\Core;

abstract class AbstractTranslatableModel extends \Eloquent
{

    /**
     * AbstractTranslatableModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes  = []){
        parent::__construct($attributes);
        if (empty($this->translationModel)
            || empty($this->translationForeignKey)
            || empty($this->translatedAttributes)) {
            throw new \Exception('This model requires these attributes must be set to work normally :
            "translationModel", "translationForeignKey", "translatedAttributes"');
            //see comments below for sample
        }
        $this->initListify();
    }

//    protected $translationModel = 'App\Models\NewsTranslation';
//
//    protected $translationForeignKey = "news_id";
//
//    public $translatedAttributes = ['title', 'slug', 'description', 'content', 'seo_keyword', 'seo_description'];
//
//    public $appends = ['link', 'metaDesc', 'metaKeyword'];

    /**
     * @var string : FQCN of translation model
     */
    protected $translationModel;

    /**
     * @var string : the foreign key column name used in translation model
     */
    protected $translationForeignKey;

    /**
     * @var array : the fields that can be translated
     */
    public $translatedAttributes;
    /**
     * @var bool
     */
    public $showAddButton = true;

    /**
     * @var array
     */
    public $updateRules = [];

    /**
     * @var array
     */
    public $updateLangs = [];

    /**
     * @var array
     */
    public $showFields = [];

    /**
     * @var array
     */
    public $dataFields = [];

    /**
     * scope for query the translation field
     * @param $query
     * @param $keyword
     * @return mixed
     */
    public function scopeFindTranslate($query, $field, $keyword)
    {
        return $query->whereHas('translations', function ($query) use ($field, $keyword){
            $query->where($field, 'like', "%{$keyword}%");
        });
    }
}