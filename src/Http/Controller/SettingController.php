<?php

namespace Trungtnm\Backend\Http\Controller;

use Illuminate\Support\Facades\Validator;
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
        $this->data['settings'] = [
            'homepage' => [
                'label'  => 'Trang chủ',
                'fields' => [
                    'Banner'  => [
                        'headline'    => [
                            'label' => 'Headline',
                        ],
                        'brief'       => [
                            'label' => 'Brief',
                        ],
                        'video_label' => [
                            'label' => 'Video label',
                        ],
                        'video_url'   => [
                            'label' => 'Video URL',
                        ],
                    ],
                    'Dịch vụ' => [
                        'tu_van'  => [
                            'label' => 'Tư vấn',
                        ],
                        'quan_ly' => [
                            'label' => 'Quản lý',
                        ],
                        'nhan_su' => [
                            'label' => 'Nhân sự',
                        ],
                    ],
                    'Footer'  => [
                        'partner'         => [
                            'label' => 'Đối tác label',
                        ],
                        'footer_headline' => [
                            'label' => 'Headline',
                        ],
                        'footer_brief'    => [
                            'label' => 'Brief',
                        ],
                    ],
                ],
            ],
            'contact'  => [
                'label'  => 'Liên hệ',
                'fields' => [
                    [
                        'company_name' => [
                            'label' => 'Tên công ty',
                        ],
                        'address'      => [
                            'label' => 'Địa chỉ',
                        ],
                        'phone'        => [
                            'label' => 'Điện thoại liên hệ',
                        ],
                        'fax'          => [
                            'label' => 'Fax',
                        ],
                        'email'        => [
                            'label' => 'Email liên hệ',
                        ],
                        'facebook'     => [
                            'label' => 'Facebook URL',
                        ],
                        'google'       => [
                            'label' => 'Google+ URL',
                        ],
                        'pinterest'    => [
                            'label' => 'Pinterest URL',
                        ],
                        'location'     => [
                            'label' => 'URL Google map',
                        ],
                    ],
                ],
            ],
        ];
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