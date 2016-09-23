<?php
namespace Trungtnm\Backend\Model;

use Lookitsatravis\Listify\Listify;
use Trungtnm\Backend\Core\AbstractModel;
use Trungtnm\Backend\Core\ModelTrait;

class Language extends AbstractModel
{
    use ModelTrait, Listify;

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        $this->initListify();
    }

    protected $table = 'languages';

    public $dirUpload = 'upload/languages/';

    public $showFields = [
        'name'         =>  [
            'label'         =>  'Language name',
            'type'          =>  'text'
        ],
        'locale'         =>  [
            'label'         =>  'Locale code',
            'type'          =>  'text'
        ],
        'flag'         =>  [
            'label'         =>  'Flag',
            'type'          =>  'image',
        ],
        'position'         =>  [
            'label'         =>  'Order',
            'type'          =>  'editable',
            'source'     => 'LanguageGetSource',
        ],
        'status'         =>  [
            'label'         =>  'Status',
            'type'          =>  'boolean'
        ],
        'created_at'    =>  [
            'label'         =>  'Created at',
            'type'          =>  'text'
        ]
    ];

    public $dataFields = [
        'name' => [
            'label' =>  'Language name',
            'type'  =>  'text'
        ],
        'locale'         =>  [
            'label'         =>  'Locale code',
            'type'          =>  'text'
        ],
        'flag'         =>  [
            'label'         =>  'Flag',
            'type'          =>  'file',
        ],
        'status' => [
            'label'     => 'Status',
            'type'      =>  'select',
            'defaultOption' => [ '1' => "Active", '0' => "Inactive"]
        ],
    ];

    public $uploadFields = [
        'flag'
    ];

    public $searchFields = [
        'name'  =>  'Language name',
        'locale'  =>  'Locale code',
    ];
    public $updateRules = [
        'name' => 'required',
        'locale' => 'required',
        'flag' => 'required',
    ];

}
