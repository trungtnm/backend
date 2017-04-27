<?php

namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Validator;
use Trungtnm\Backend\Core\AbstractSetting;
use Trungtnm\Backend\Core\CoreBackendController;

class SettingController extends CoreBackendController
{
    /**
     * @var \Trungtnm\Backend\Helpers\Settings
     */
    protected $settingService;

    public function __construct()
    {
        $this->init();
        $this->settingService = app('Trungtnm\Backend\Helpers\Settings', [config_path('settings.yml')]);
    }

    public function indexAction()
    {
        $appSettings = null;
        try {
            $appSettings = app(config('trungtnm.backend.settingClass'));
        } catch (\Exception $e) {
            $this->data['error'] = "Setting class not found";
        }
        if (!$appSettings || !$appSettings instanceof AbstractSetting) {
            throw new \Exception("Setting class must be an instance of " . AbstractSetting::class);
        }

        $this->data['settings'] = $appSettings->build();
        $this->data['values'] = $this->settingService->getAll();

        return view('TrungtnmBackend::setting.index', $this->data);
    }

    public function editAction($id = 0)
    {
        $this->processData();

        return redirect(route('SettingIndex'));
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function processData($id = 0)
    {
        $data = request('settings');
        $rules = [
            'homepage_headline'  => 'required',
            'homepage_brief'     => 'required',
            'homepage_video_url' => 'required',
        ];
        $validator = Validator::make($data, $rules);
        if ($data && $validator->passes()) {
            $this->settingService->save($data);
        }
    }
}