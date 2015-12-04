<?php
namespace Trungtnm\Backend;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    protected $defaultTitle = " - toicuocsong.com";
    /**
     * registered directives
     * @var array
     */
    private $directives = [
       'title', 'active', 'number'
    ];
    public function boot()
    {
        foreach ($this->directives as $directive) {
            $this->{$directive}();
        }
    }
    public function register(){}

    /**
     * generate page meta title
     */
    private function title()
    {
        \Blade::directive('title', function($title) {
                return "<?php echo $title  .  '{$this->defaultTitle}';?>";
        });
    }

    /**
     * use for checking active state of navigation link
     */
    private function active()
    {
        \Blade::directive('active', function($routeName, $activeClass = 'active') {
            return "<?php echo {$routeName} == \\Route::getCurrentRoute()->getName() ? '{$activeClass}' : ''; ?>";
        });
    }

    /**
     * quick output number format
     */
    private function number()
    {
        \Blade::directive('number', function($number, $separator = 0, $lang='vn') {
            if($lang=='vn')
                return "<?php echo number_format($number, $separator, ',', '.') ?>";
            else
                return  "<?php echo number_format($number, $separator, '.', ',')?>";
        });
    }

}