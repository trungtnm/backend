<?php
namespace Trungtnm\Backend\Core;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Sentinel;
use Trungtnm\Backend\Model\Menu;
use Trungtnm\Backend\Utility\FtpUtil;
use Trungtnm\Backend\Utility\HtmlMaker;

class CoreBackendController extends BaseController
{

    protected $data          = [];
    protected $updateData    = [];
	protected $model         = null;
	protected $defaultField  = 'updated_at';
	protected $defaultOrder  = 'desc';
	protected $showAddButton = true;
	protected $searchSelects = [];
	protected $searchFields = [];

    public function init()
    {
        View::share('assetURL', asset('vendor/trungtnm/backend') . "/");
        $this->data['backendUrl'] = url(config('trungtnm.backend.uri')) . "/";
        if (Sentinel::check()) {
            $this->data['menus'] = $this->getMenu();
            // get url of module
            $module = strtolower(request()->segment(2));
            $menu = Menu::where([
                'slug' => $module,
                'status' => 1
            ])->first();
            $this->data['module'] = 'backend';
            if ($menu) {
                $this->data['module'] = ucfirst($menu->module);
                $this->data['defaultURL'] = $menu->slug;
            } elseif ($module != "access-denied") {
                return Redirect::route('accessDenied');
            }
            $this->data['model'] = $this->model;
            $this->data['maker'] = new HtmlMaker();
        }
    }

	public function redirectAfterSave($type, $message = null, $status = null )
    {
		switch ($type) {
			case 'save-return':
				return Redirect::route($this->data['module']. "Index");
				break;

			case 'save-new':
				return Redirect::route($this->data['module'].'Create');
				break;

			case 'save':
				return Redirect::route(
                        $this->data['module'].'Update',
                        $this->data['id']
                    )->with(
                        ['message' => $message]
                    );
				break;
			
			default:
				return Redirect::route($this->data['module'].'Index');
				break;
		}
	}


	public function indexAction(){
		$this->data['defaultField']  = $this->defaultField;
		$this->data['defaultOrder']  = $this->defaultOrder;
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
        $this->data['showFields'] 	= $this->model->showFields;


        $this->data['lists'] =
            $this->model->search($keyword, $filterBy)
                ->orderBy($defaultField, $defaultOrder)
                ->paginate($showNumber);

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
				return Redirect::route($this->data['module'].'Index');
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

        if ($this->model->seo) {
            $this->addSeo();
        }

		return view('TrungtnmBackend::general.edit', $this->data);

	}

    /**
     * get menus with permission read of user
     *
     * @return array
     */
    public function getMenu(){
        $listMenusTMP 	= Menu::getList();
        $listMenus 		= array();
        if( !empty($listMenusTMP) ){
            foreach( $listMenusTMP as $menu ){
                if (Sentinel::hasAccess($menu->module . ".*")) {
                    if( $menu->parent_id == 0 ){
                        $listMenus[$menu->id] = $menu;
                    }
                    else{
                        if(!empty($listMenus[$menu->parent_id])){
                            $listMenus[$menu->parent_id]['children'][] = $menu;
                        }
                    }
                }
            }
        }
        return $listMenus;
    }

	/**
	 * add 2 seo_keyword and seo_description to dataFields if seo enable
	 */
	public function addSeo() {
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
			$this->processData($id);
			//additional SEO fields
			$this->handleSeo();
			// File Upload
            $this->handleFileUpload($id);
			// End file Upload
			$item = $this->fillData();

			if($item->save()){
				$this->data['id'] = $item->id;
				$item->renewCache();
                $this->afterSave($item);
				return true;
			}

		}else{
            $this->data['validate'] = $validate->messages();
		}

		return false;

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
                if(is_array($this->model->uploadFields)){
                    foreach ($this->model->uploadFields as $field) {
                        if(!empty($item->$field)){
                            @unlink($item->$field);
                        }
                    }
                }
                return "success";
            }
        }
		return "fail";
	}

    /**
     * handle SEO fields
     */
    public function handleSeo()
    {
        if($this->model->seo){
            $this->updateData['seo_description'] = request('seo_description');
            $this->updateData['seo_keyword']     = request('seo_keyword');
        }
    }

    /**
     * handle file uploads
     */
    public function handleFileUpload($id = 0)
    {
        if(!is_array($this->model->uploadFields)) {
            return;
        }

        foreach ($this->model->uploadFields as $field) {
            if(request('inputURL_'. $field)){
                $this->updateData[$field] = request($field);
            }
            else{
                if(Input::hasFile($field)){
                    $this->updateData[$field] = $this->uploadImage($field, $this->model->dirUpload);
                }
            }
            if($id > 0){
                //update - delete old file
                if (!empty($this->data['item']->$field) && $this->data['item']->$field != $this->updateData[$field]) {
                    @unlink($this->data['item']->$field);
                }
            }
        }
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

	public function logging($dataLog){
    //TODO: do some logging here
	}

    /**
     * fill data to model before saving
     * @param  [array] $data model data to be saved into database
     * @return new object model with updated data
     */
    public function fillData(){
        $item = clone $this->model;
        if (is_array($this->updateData)) {
            if (!empty($this->updateData['id'])) {
                $item = $item->find($this->updateData['id']);
            }
            foreach ($this->updateData as $field => $value) {
                $item->$field = $value;
            }
        }
        return $item;

    }

    public function uploadImage($name, $dirUpload, $width = 0, $height = 0){
        if(Input::hasFile($name)){
            $dirUpload = $dirUpload ? trim($dirUpload, '/') . "/" : config('trungtnm.backend.uploadFolder');
            $input         = Input::file($name);
            $realPath      = $input->getRealPath();

            if($width != 0 && $height != 0 ){
                $uploadFile    = Image::make($input)->resize($width, $height)->save($realPath);
            }
            $filename = $input->getClientOriginalName();
            $nameArr  = explode('.', $filename);
            $extension = is_array($nameArr) && count($nameArr) > 1 ? end($nameArr) : '';
            if($extension){
                array_pop($nameArr);
                $filename = implode('.', $nameArr);
            }

            $imgName       = str_slug($filename) . "-" . time() . "." . strtolower($extension);
            $uploadSuccess = $input->move($dirUpload, $imgName);
            $imagePath     = $dirUpload. $imgName;
            if( $uploadSuccess ){
                return $imagePath;//.'.'.$fileExtension;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    public function uploadFTP($name, $dirUpload, $connection = ''){
        if(Input::hasFile($name)){
            $dirUpload = $dirUpload ? "/" . trim($dirUpload, '/') . "/" : config('trungtnm.backend.uploadFolder');
            $input         = Input::file($name);
            $originalFileName = $input->getClientOriginalName();
            $nameArr  = explode('.', $originalFileName);
            $extension = is_array($nameArr) && count($nameArr) > 1 ? end($nameArr) : '';
            if($extension){
                array_pop($nameArr);
                $fileName = implode('.', $nameArr);
            }
            $imgName = str_slug($fileName) . "-" . time() . "." . strtolower($extension);

//            $uploadSuccess = (new FtpUtil())->uploadFtp($dirUpload, $originalFileName, $imgName);
            $uploadSuccess = true;
            if( $uploadSuccess ){
                $connection = !$connection ? config('ftp::default') : $connection;
                $FtpConnectionsConfigs = config('ftp::connections');
                $FtpConfigs = $FtpConnectionsConfigs[$connection];
                $remotePath = $FtpConfigs['basic_url'] . "/" . trim($dirUpload, "/") . "/" . $imgName;
                return $remotePath;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}
