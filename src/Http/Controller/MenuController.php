<?php
namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Request;
use Sentinel;
use Trungtnm\Backend\Core\AbstractBackendController;
use Trungtnm\Backend\Model\Menu;
use Trungtnm\Backend\Model\Module;

class MenuController extends AbstractBackendController
{
    protected $data = [];

    protected $module = 'Menu';

	public function __construct(Menu $model) {
        //TODO: install for 1st times run this package
        $this->model = $model;
        $this->init();
	}

    public function getEditData()
    {
        $this->data['parent']  = array_column(
            $this->model->where('id', '!=', $this->data['id'])
                ->get()
                ->toArray(),
            'name',
            'id'
        );
        $this->data['modules']  = array_column(Module::all()->toArray(), 'name', 'id');
        $this->data['customView'] = view('TrungtnmBackend::menu.edit', $this->data)->render();
    }

	public function processData($id = 0){

        $updateData = array(
            'id'        =>  $id,
            'status'    =>	(int) request('status'),
            'name'      =>	trim(request('name')),
            'module_id' =>	trim(request('module_id')),
            'parent_id' =>	intval(request('parent_id')),
            'slug'      =>	trim(request('slug')),
            'icon'      =>	trim(request('icon')),
        );

        return $updateData;

	}

	public function nestableAction(){
		$this->data['defaultURL'] 	= $this->moduleURL;
		$this->data['listMenus'] = $this->getMenu();

		$this->layout->content = View::make('ShowNestable', $this->data);
	}

    public function saveNestableAction(){

        $listMenus = request('menu');
        $order = 0;

        if( !empty($listMenus) ){
            foreach( $listMenus as $id => $parent ){
                $order++;
                $menu = Menu::where('id', $id)->update(array('parent_id' => intval($parent), 'order' => $order));
            }
        }

        return response()->json(TRUE);

    }

	public function getMenu(){
		$listMenusTMP 	= Menu::getList();
		$listMenus 		= array();
		if( !empty($listMenusTMP) ){
			foreach( $listMenusTMP as $lm ){
				if(Sentinel::getUser()->hasAccess(snake_case($lm->module->name, '-') . '-read')){
					if( $lm->parent_id == 0 ){
						$listMenus[$lm->id] = $lm;
					}
					else{
						if(Sentinel::getUser()->hasAccess(snake_case($lm->module->name, '-') . "-read")){
							if(!empty($listMenus[$lm->parent_id])){
								$listMenus[$lm->parent_id]['children'][] = $lm;
							}
						}
							
					}
				}
				
			}
		}
		return $listMenus;

	}


	public function render(){

		$listModuleFile = array();
		$listIgnores = array(
			'dashboard',
			'home',
			'chat',
			'search',
			'.',
			'..',
			'.DS_Store',
			'.svn',
			'sample'
		);
		$primaryArray = array(
			'Create','Read','Edit','Delete'
		);
		$listFiles = array_diff(scandir( $this->modulePath ), $listIgnores);
		// GET LIST FILE IN FOLDER
		if( !empty($listFiles) ){
			foreach( $listFiles as $file ){
				$file = str_replace("_backend","", $file);
				
				$fileName = ucwords(str_replace("_"," ", $file));				
				$fileSlug = str_replace("_","-", $file);

				$listModuleFile[$fileSlug] = $fileName;
			}
		}


		// GET LIST FILE IN DATABASE
		$listModuleStore = Modules::get()->toArray();
		$listModuleStore = array_column($listModuleStore, 'name', 'slug');
		// NEW MODULE
		// pr($listModuleStore);
		$diffInsert = array_diff($listModuleFile, $listModuleStore);
		$insertData = array();
		if( !empty($diffInsert) ){
			foreach( $diffInsert as $k => $v ){
				$insertData = array(
					'slug'	=>	$k,
					'name'	=>	$v,
					'status'=>	1
				);
				$item = new Modules;
				$item->slug = $k;
				$item->name = $v;
				$item->status = 1;

				if($item->save()){

					// Insert Menu
					$menu = new Menus;
					$menu->status = 0;
					$menu->name = $item->name;
					$menu->module_id = $item->id;
					$menu->slug = $item->slug.'/show-list';
					$menu->save();
					// Create Primary Permission
					foreach($primaryArray as $p ){
						$perms            = new Permission;
						$perms->name      =	$item->name." ".$p;
						$perms->slug      =	$item->slug."-".strtolower($p);
						$perms->module_id =	$item->id;
						$perms->action    =	strtolower($p);
						$perms->save();
					}
				}

			}	
		}

		// REMOVE MODULE
		$diffRemove = array_diff($listModuleStore, $listModuleFile);

		if( !empty($diffRemove) ){
			foreach( $diffRemove as $k => $v ){
				$item = Modules::where(array('slug'	=>	$k,'name'	=>	$v))->first();
				try{

					$deleteID = $item->id;
				}
				catch(Exception $e){
					pr($diffRemove,1);
				}
				if( $item->delete() ){
					Menu::where('module_id', $deleteID)->delete();
					Permission::where('module_id', $deleteID)->delete();
				}
			}
		}

	}
	/*----------------------------- END CREATE & UPDATE --------------------------------*/

	/*----------------------------- DELETE --------------------------------*/

	public function deleteAction(){

		if( Request::ajax() ){
			$id 	= request('id');
			$item 	= $this->model->find($id);
			if( $item ){
				$parent_id = $item->parent_id;
				if($item->delete()){
					if( $parent_id == 0 ){
						$this->model->where('parent_id', $id)->update(array(
							'parent_id'	=>	0
						));
					}
					return response("success");
				}
			}
		}
		return response("fail");

	}

	/*----------------------------- END DELETE --------------------------------*/

	
}
