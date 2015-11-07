<?php
namespace Trungtnm\Backend\Core;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Trungtnm\Backend\Model\Module;
use Trungtnm\Backend\Utility\HtmlMaker;

class AbstractBackendController extends BaseController {

	public $layout        = 'layout.backend';
    protected $data          = [];
	protected $module        = null;
	protected $model         = null;
	protected $moduleURL     = null;
	protected $moduleName    = null;
	protected $defaultField  = 'updated_at';
	protected $defaultOrder  = 'desc';
	protected $uploadFields  = [];
	protected $showAddButton = true;
	protected $searchSelects = [];
	protected $searchFields = [];

	protected $seo = false;

	protected $dataFields = [];

	protected $UpdateRules = [];

	protected $UpdateLang = [];

    public function init()
    {
        View::share('assetURL', asset('vendor/trungtnm/backend') . "/");

        // get url of module
        $module = Module::where('slug', strtolower($this->module))->first();
        if(!empty($module))
            $this->moduleName = $module->moduleName;
        $this->data['moduleName'] = $this->moduleName;
        $this->data['module'] = $this->module;
        $this->data['model'] = $this->model;
        $this->data['maker'] = new HtmlMaker();
    }

	public function redirectAfterSave($type, $message = null, $status = null )
    {
		switch ($type) {
			case 'save-return':
				return Redirect::route($this->module. "Index");
				break;

			case 'save-new':
				return Redirect::route($this->module.'Create');
				break;

			case 'save':
				return Redirect::route(
                        $this->module.'Update',
                        $this->data['id']
                    )->with(
                        ['message' => $message]
                    );
				break;
			
			default:
				return Redirect::route($this->module.'Index');
				break;
		}
	}


	public function indexAction(){
		$this->data['defaultField']  = $this->defaultField;
		$this->data['defaultOrder']  = $this->defaultOrder;
		$this->data['defaultURL']    = $this->moduleURL;
		$this->data['showAddButton'] = $this->showAddButton;
		$this->data['searchFields']  = $this->model->searchFields;
		$this->data['searchSelects'] = $this->model->searchSelects;

		//get additional show list page data
		$this->getIndexData();

        return view('TrungtnmBackend::general.index', $this->data);
	}

	public function adapterAction(){
        $this->layout 	= null;
        $defaultField 	= request('defaultField');
        $defaultOrder 	= request('defaultOrder');
        $keyword 		= trim(request('keyword')) ;
        $filterBy 		= request('filterBy');
        $showNumber 	= request('showNumber');
        $this->data['defaultField'] = $defaultField;
        $this->data['defaultOrder'] = $defaultOrder;
        $this->data['defaultURL'] 	= $this->moduleURL;
        $this->data['showFields'] 	= $this->model->showFields;


        $this->data['lists'] = $this->model->search($keyword, $filterBy)->orderBy($defaultField, $defaultOrder)->paginate($showNumber);
//        pr((array) $this->data['lists'],1);
        return view('TrungtnmBackend::general.adapter', $this->data);
	}

	public function editAction($id = 0){
		$this->data['id'] = $id;
		// WHEN UPDATE SHOW CURRENT INFORMATION
		if( $id != 0 ){

			$item = $this->model->find($id);
			if( $item ){
				$this->data['item'] = $item;
			}else{
				return Redirect::route($this->module.'Index');
			}
		}

		if (Request::isMethod('post'))
		{
			if( $this->saveObject($id, $this->data) ){
				return $this->redirectAfterSave(request('save'));
			}
		}


		$this->data['dataFields'] = $this->model->dataFields;

		//get additional data for page update
		$this->getEditData();

		return view('TrungtnmBackend::general.edit', $this->data);

	}
	/**
	 * add 2 seo_keyword and seo_description to dataFields if seo enable
	 */
	public function addSeoFieldsAction() {
		$this->data['dataFields']['seo_keyword'] = [
			'label' =>  'SEO Keyword',
	        'type'  =>  'textarea',
		];
		$this->data['dataFields']['seo_description'] = [
			'label' =>  'SEO description',
	        'type'  =>  'textarea',
		];
		return true;
	}

	/**
	 * Additional Data send to show-list page
	 * @return [type] [description]
	 */
	public function getIndexData(){
		// sample : get data for Select
		// $this->data['partners'] = AdvertisePartner::select('id','name')->get();
		return true;
	}

	/**
	 * Additional Data send to update form
	 * @return [type] [description]
	 */
	public function getEditData(){
		// sample : get data for Select
		// $this->data['partners'] = AdvertisePartner::select('id','name')->get();
        // $this->data['customView'] = view('...');
		return true;
	}


	public function saveObject($id = 0, &$data){
		// check validate
		$validate 		= Validator::make(Input::all(), $this->model->updateRules, $this->model->updateLangs);

		if( $validate->passes() ){
			//subclass must override this method to proccess saving data
			$updateData = $this->processData($id);
			//additional SEO fields
			if($this->seo){
				$updateData['seo_description'] = request('seo_description');
				$updateData['seo_keyword']     = request('seo_keyword');
			}
			// File Upload
			if(is_array($this->uploadFields)){
				foreach ($this->uploadFields as $field) {
					if(request('inputURL_'. $field)){
						$updateData[$field] = request($field);
					}
					else{
						if(Input::hasFile($field)){
							$updateData[$field] = uploadImage($field, $this->dirUpload);
						}
					}
					if($id > 0){
						//update - delete old file
					}
				}
			}
			// End file Upload	
			$model = get_class($this->model);
			$item = (new $model )->fillData($updateData);

			if($item->save()){
				$this->data['id'] = $item->id;
				$item->renewCache();
                $this->afterSave($item);
				return true;
			}

		}else{
			$data['validate'] = $validate->messages();
		}

		return FALSE;

	}
	/**
	 * Process data before save, child controller must extend this method to customize data saving
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
    public function processData($id = 0){
		$updateData = array(
			'id'            => 	$id,
			//sample data
			// 'status'        =>	(int) request('status'),
			// 'campaign_name' =>	request('campaign_name'),
			// 'id_partner'    =>	request('id_partner'),
			// 'cat_id'        =>	request('cat_id'),
			// 'campaign_type' =>	request('campaign_type'),
			// 'date_start'    =>	request('date_start'),
			// 'date_end'      =>	request('date_end'),
		);
		return $updateData;
	}


	public function toggleAction(){

        $id 			= request('id');
        $field 			= request('field');
        $currentValue 	= request('value');

        $value = ( $currentValue == 1 ) ? 0 : 1;

        $item = $this->model->find($id);
        if( $item ){
            $item->{$field} = $value;
            if( $item->save() ){
                $item->renewCache();
                $this->data['field'] = $field;
                $this->data['item'] = $item;
                return view('TrungtnmBackend::general.toggle', $this->data);
            }
        }
	}


	public function deleteAction(){
        $id 	= request('id');
        $item 	= $this->model->find($id);
        if( $item ){

            if($item->delete()){
                $this->afterSave($item, true);
                $item->renewCache();
                // TO DO : move to config
                if(is_array($this->uploadFields)){
                    foreach ($this->uploadFields as $field => $info) {
                        if(!empty($item->$field)){
                            unlink($item->$field);
                        }
                    }
                }
                return "success";
            }
        }
		return "fail";
	}

    /**
     * called after save or delete item
     *
     * @param $item
     * @param bool|false $isDelete
     */
    public function afterSave($item, $isDelete = false)
    {

    }

	public function logging( $dataLog ){
    //TODO: do some logging here
	}


}
