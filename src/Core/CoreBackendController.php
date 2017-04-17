<?php
namespace Trungtnm\Backend\Core;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Sentinel;
use Trungtnm\Backend\Model\Language;
use Trungtnm\Backend\Model\Menu;
use Trungtnm\Backend\Model\User;
use Trungtnm\Backend\Utility\HtmlMaker;

abstract class CoreBackendController extends BaseController implements BackendControllerInterface
{
    const ACTION_CREATE = 'create';
    const ACTION_EDIT = 'edit';
    const ACTION_DELETE = 'delete';
    const ACTION_PUBLISH = 'publish';

    protected $data = [];
    protected $updateData = [];
    protected $model = null;
    protected $defaultField = 'updated_at';
    protected $defaultOrder = 'desc';
    protected $showAddButton = true;
    protected $showEditButton = true;
    protected $showDeleteButton = true;
    protected $searchSelects = [];
    protected $searchFields = [];
    /**
     * @var User
     */
    protected $user;
    /**
     * @return mixed
     */
    public function init()
    {
        View::share('assetURL', asset('vendor/trungtnm/backend') . "/");
        $this->data['backendUrl'] = url(config('trungtnm.backend.uri')) . "/";
        if (Sentinel::check()) {
            $this->data['menus'] = $this->getMenu();
            // get url of module
            $module = strtolower(request()->segment(2));
            $menu = Menu::where([
                'slug'   => $module,
                'status' => 1
            ])->first();
            $this->data['module'] = 'backend';
            if ($menu) {
                $this->data['moduleName'] = ucfirst($menu->name);
                $this->data['module'] = ucfirst($menu->module);
                $this->data['defaultURL'] = $menu->slug;
            } elseif ($module != "access-denied") {
                return Redirect::route('accessDenied');
            }
            $this->data['model'] = $this->model;
            $this->data['maker'] = new HtmlMaker();
        }
        $this->user = Sentinel::getUser();
    }

    public function redirectAfterSave($type, $message = null, $status = null)
    {
        switch ($type) {
            case 'save-return':
                return Redirect::route($this->data['module'] . "Index");
                break;

            case 'save-new':
                return Redirect::route($this->data['module'] . 'Create');
                break;

            case 'save':
                return Redirect::route(
                    $this->data['module'] . 'Update',
                    $this->data['id']
                )->with(
                    ['message' => $message]
                );
                break;

            default:
                return Redirect::route($this->data['module'] . 'Index');
                break;
        }
    }


    public function indexAction()
    {
        $this->data['defaultField'] = $this->defaultField;
        $this->data['defaultOrder'] = $this->defaultOrder;
        $this->data['showAddButton'] = $this->showAddButton;
        $this->data['searchFields'] = $this->model->searchFields;
        $this->data['searchSelects'] = $this->model->searchSelects;

        //get additional show list page data
        $this->getIndexData();

        return view('TrungtnmBackend::general.index', $this->data);
    }

    public function adapterAction()
    {
        $this->layout = null;
        $defaultField = request('defaultField');
        $defaultOrder = request('defaultOrder');
        $keyword = trim(request('keyword'));
        $filterBy = request('filterBy');
        $showNumber = request('showNumber');
        $this->data['defaultField'] = $defaultField;
        $this->data['defaultOrder'] = $defaultOrder;
        $this->data['showFields'] = $this->model->showFields;
        $this->data['showDeleteButton'] = $this->showDeleteButton && Sentinel::hasAccess([str_slug($this->data['module']) . '.delete']);
        $this->data['showEditButton'] = $this->showEditButton && Sentinel::hasAccess([str_slug($this->data['module']) . '.edit']);

        $this->data['lists'] =
            $this->model->search($keyword, $filterBy)
                ->orderBy($defaultField, $defaultOrder)
                ->paginate($showNumber);

        $this->getAdapterData();

        return view('TrungtnmBackend::general.adapter', $this->data);
    }

    /**
     * edit and create action
     *
     * @param int $id
     *
     * @return View
     */
    public function editAction($id = 0)
    {
        $this->data['id'] = $id;
        // WHEN UPDATE SHOW CURRENT INFORMATION
        if ($id != 0) {
            $item = $this->model->find($id);
            if ($item) {
                $this->data['item'] = $item;
            } else {
                return Redirect::route($this->data['module'] . 'Index');
            }
        } else {
            $this->data['item'] = $this->model;
        }

        if (Request::isMethod('post')) {
            if ($this->saveObject($id)) {
                return $this->redirectAfterSave(request('save'));
            }
        }

        $this->data['dataFields'] = $this->model->dataFields;

        //get additional data for page update
        $this->getEditData();

        if ($this->model->seo) {
            $this->addSeo();
        }

        // get langs for translation model
        if (!empty($this->model->translatedAttributes)) {
            $this->data['langs'] = Language::active()->get();
        }

        return view('TrungtnmBackend::general.edit', $this->data);

    }

    /**
     * sub controller must define method "getSource" . $field and return the data
     *
     * @param $id
     * @param $field
     *
     * @return string
     */
    public function getSourceAction($id, $field)
    {
        $item = $this->model->find($id);
        if ($item && $field) {
            $method = "getSource" . $field;

            return $this->{$method}($item);
        }

        return '';
    }

    /**
     * @param null $item
     *
     * @return string
     */
    public function getSourcePosition($item = null)
    {
        $result = [[
            'value' => null, 'text' => 'N/A'
        ]];
        for ($i = 1; $i <= 20; $i++) {
            $result[] = [
                'value' => $i,
                'text'  => $i
            ];
        }

        return json_encode($result);
    }

    /**
     * @param $id
     * @param $field
     *
     * @return string
     */
    public function saveSourceAction($id, $field)
    {
        $response = ['Save errors'];
        $item = $this->model->find($id);
        if ($item && $field) {
            $saveMethod = 'quickEdit' . ucfirst($field);
            if (method_exists($this, $saveMethod)) {
                $this->{$saveMethod}($item, request('value'));
            } else {
                $item->{$field} = request('value');
                $item->save();
            }
            $this->afterSave($item, self::ACTION_EDIT);
            $response = [
                'id' => $item->id
            ];
        }

        return json_encode($response);
    }

    /**
     * @param $item
     * @param $position
     *
     * @return mixed
     */
    public function quickEditPosition($item, $position)
    {
        return $item->setListPosition($position);
    }

    /**
     * set the sort order position
     *
     * @return string
     */
    public function orderAction()
    {
        $position = intval(request('position'));
        if ($position > 0) {
            if ($this->model->setListPosition($position)) {
                return 'true';
            }
        }

        return 'false';
    }

    /**
     * get menus with permission read of user
     *
     * @return array
     */
    public function getMenu()
    {
        $listMenusTMP = Menu::getList();
        $listMenus = new Collection();
        if (!empty($listMenusTMP)) {
            foreach ($listMenusTMP as $menu) {
                $item = clone $menu;
                if (Sentinel::hasAccess(strtolower($menu->module) . ".*")) {
                    $item->children->each(function($child, $key) use ($item) {
                        if (!$child->status || !Sentinel::hasAccess(strtolower($child->module) . ".*")) {
                            $item->children->forget($key);
                        }
                    });
                    $listMenus->add($item);
                }
            }
        }

        return $listMenus;
    }

    /**
     * add 2 seo_keyword and seo_description to dataFields if seo enable
     */
    public function addSeo()
    {
        $this->data['dataFields']['seo_keyword'] = [
            'label' => 'SEO Keyword',
            'type'  => 'textarea',
        ];
        $this->data['dataFields']['seo_description'] = [
            'label' => 'SEO description',
            'type'  => 'textarea',
        ];

        return true;
    }

    /**
     * Additional Data send to show-list page
     *
     * @return [type] [description]
     */
    public function getIndexData()
    {
        // sample : get data for Select
        // $this->data['partners'] = AdvertisePartner::select('id','name')->get();
        return true;
    }

    /**
     * Additional Data send to update form
     *
     * @return [type] [description]
     */
    public function getEditData()
    {
        // sample : get data for Select
        // $this->data['partners'] = AdvertisePartner::select('id','name')->get();
        // $this->data['customView'] = view('...');
        return true;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function saveObject($id = 0)
    {
        $this->processData($id);
        $validateData = array_merge($this->updateData, request()->all());
        $validate = Validator::make($validateData, $this->model->updateRules, $this->model->updateLangs);
        if ($validate->passes()) {
            //additional SEO fields
            $this->handleSeo();
            // File Upload
            $this->handleFileUpload($id);
            // End file Upload
            $item = $this->fillData();
            if ($item->save()) {
                $this->data['id'] = $item->id;
                //handle translations data
                $this->translate($item);
                $item->renewCache();
                $action = !$id ? self::ACTION_CREATE : self::ACTION_EDIT;
                $this->afterSave($item, $action);

                return true;
            }

        } else {
            $this->data['validate'] = $validate->messages();
        }

        return false;

    }

    /**
     * @return View
     */
    public function toggleAction()
    {

        $id = request('id');
        $field = request('field');
        $currentValue = request('value');

        $value = ($currentValue == 1) ? 0 : 1;

        $item = $this->model->find($id);
        if ($item) {
            $item->{$field} = $value;
            if ($item->save()) {
                $item->renewCache();
                $this->data['field'] = $field;
                $this->data['item'] = $item;

                return view('TrungtnmBackend::general.toggle', $this->data);
            }
        }
    }

    /**
     * @return string
     */
    public function deleteAction()
    {
        $id = request('id');
        $item = $this->model->find($id);
        if ($item) {
            $item->delete();
            //delete translation
            if (!empty($item->translatedAttributes)) {
                $item->translations()->delete();
            }
            $this->afterSave($item, self::ACTION_DELETE);
            // TO DO : move to config
            if (!empty($this->mode) && !empty($this->model->uploadFields) && is_array($this->model->uploadFields)) {
                foreach ($this->model->uploadFields as $field) {
                    if (!empty($item->$field)) {
                        @unlink($item->$field);
                    }
                }
            }

            return "success";
        }

        return "fail";
    }

    /**
     * handle SEO fields
     */
    public function handleSeo()
    {
        if ($this->model->seo && empty($this->model->translatedAttributes)) {
            $this->updateData['seo_description'] = request('seo_description');
            $this->updateData['seo_keyword'] = request('seo_keyword');
        }
    }

    /**
     * handle file uploads
     */
    public function handleFileUpload($id = 0)
    {
        if (!is_array($this->model->uploadFields)) {
            return;
        }

        foreach ($this->model->uploadFields as $field) {
            if (request('inputURL_' . $field)) {
                $this->updateData[$field] = request($field);
            } else {
                if (Input::hasFile($field)) {
                    $uploadFolder = $this->detectUploadFolder($field);
                    $this->updateData[$field] = $this->uploadImage($field, $uploadFolder);
                }
            }
            if ($id > 0 && !empty($this->updateData[$field])) {
                //update - delete old file
                if (!empty($this->data['item']->$field) && $this->data['item']->$field != $this->updateData[$field]) {
                    @unlink($this->data['item']->$field);
                }
            }
        }
    }

    /**
     * @param $field
     *
     * @return string
     */
    private function detectUploadFolder($field)
    {
        if (is_array($this->model->dirUpload)) {
            if (array_key_exists($field, $this->model->dirUpload)) {
                $uploadFolder = $this->model->dirUpload[$field];
            } else {
                $uploadFolder = "";
            }
        } else {
            $uploadFolder = $this->model->dirUpload;
        }

        return $uploadFolder;
    }

    /**
     * called after save or delete item
     *
     * @param        $item
     * @param string $action
     */
    public function afterSave($item, $action = '')
    {
    }

    public function logging($dataLog)
    {
        //TODO: do some logging here
    }

    /**
     * fill data to model before saving
     *
     * @param  [array] $data model data to be saved into database
     *
     * @return new object model with updated data
     */
    public function fillData()
    {
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

    /**
     * @param     $name
     * @param     $dirUpload
     * @param int $width
     * @param int $height
     *
     * @return bool|string
     */
    public function uploadImage($name, $dirUpload)
    {
        if (Input::hasFile($name)) {
            $dirUpload = $dirUpload ? trim($dirUpload, '/') . "/" : config('trungtnm.backend.uploadFolder');
            $input = Input::file($name);
            $realPath = $input->getRealPath();
            $filename = $input->getClientOriginalName();
            $nameArr = explode('.', $filename);
            $extension = is_array($nameArr) && count($nameArr) > 1 ? end($nameArr) : '';
            if ($extension) {
                array_pop($nameArr);
                $filename = implode('.', $nameArr);
            }

            $imgName = str_slug($filename) . "-" . time() . "." . strtolower($extension);
            $uploadSuccess = $input->move($dirUpload, $imgName);
            $imagePath = $dirUpload . $imgName;
            if ($uploadSuccess) {
                return $imagePath;//.'.'.$fileExtension;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param        $name
     * @param        $dirUpload
     * @param string $connection
     *
     * @return bool|string
     */
    public function uploadFTP($name, $dirUpload, $connection = '')
    {
        if (Input::hasFile($name)) {
            $dirUpload = $dirUpload ? "/" . trim($dirUpload, '/') . "/" : config('trungtnm.backend.uploadFolder');
            $input = Input::file($name);
            $originalFileName = $input->getClientOriginalName();
            $nameArr = explode('.', $originalFileName);
            $extension = is_array($nameArr) && count($nameArr) > 1 ? end($nameArr) : '';
            if ($extension) {
                array_pop($nameArr);
                $originalFileName = implode('.', $nameArr);
            }
            $imgName = str_slug($originalFileName) . "-" . time() . "." . strtolower($extension);

            $uploadSuccess = true;
            if ($uploadSuccess) {
                $connection = !$connection ? config('ftp::default') : $connection;
                $FtpConnectionsConfigs = config('ftp::connections');
                $FtpConfigs = $FtpConnectionsConfigs[$connection];
                $remotePath = $FtpConfigs['basic_url'] . "/" . trim($dirUpload, "/") . "/" . $imgName;

                return $remotePath;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * handle the translation data models by languages
     *
     * @param $item
     *
     * @return bool
     */
    private function translate($item)
    {
        if (!empty($item->translatedAttributes)) {
            $langs = Language::active()->get();
            if (count($langs)) {
                foreach ($langs as $lang) {
                    $translationModel = $item->translateOrNew($lang->locale);
                    $foreignKey = $item->getRelationKey();
                    $translationModel->{$foreignKey} = $item->id;
                    foreach ($item->translatedAttributes as $translationField) {
                        $translationModel->{$translationField} = request($translationField . "_" . $lang->locale);
                    }
                    $translationModel->save();
                }

                return true;
            }
        }

        return false;
    }

    protected function getAdapterData()
    {

    }

}
