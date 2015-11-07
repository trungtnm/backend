<?php
namespace Trungtnm\Backend\Core;

use Eloquent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AbstractModel extends Eloquent{
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
     * @param array $showField
     */
	public function __construct(){
		parent::__construct();
	}
}