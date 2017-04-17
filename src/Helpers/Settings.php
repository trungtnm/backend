<?php


namespace Trungtnm\Backend\Helpers;


use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Settings
{
    static $settings = [];

    private $path;

    public function __construct($path)
    {
        $this->path = $path;
        try {
            $this->loadSettings();
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
    }

    public static function get($key, $default = null)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : $default;
    }

    public function save($settings)
    {
        foreach ($settings as $key => $setting) {
            $this->writeSetting($key, $setting, $overwrite = true, false);
        }
        $this->write();
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $overwrite
     *
     * @param bool $write
     *
     * @return $this
     */
    public function writeSetting($key, $value, $overwrite = false, $write = true)
    {
        return $this->_set($key, $value, $overwrite, $write);
    }

    public function getAll()
    {
        return self::$settings;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function getSetting($key)
    {
        return $this->_get($key);
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $overwrite
     *
     * @param bool $write
     *
     * @return $this
     */
    private function _set($key, $value, $overwrite = true, $write = true)
    {
        if (isset(self::$settings[$key]) && !$overwrite) {
            self::$settings[$key] = array_merge(self::$settings[$key], $value);
        } else {
            self::$settings[$key] = $value;
        }
        if ($write) {
            $this->write();
        }


        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    private function _get($key)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }

    /**
     * Update content of parameter file
     */
    private function write()
    {
        file_put_contents($this->path, Yaml::dump(self::$settings));
    }

    public function loadSettings()
    {
        self::$settings = Yaml::parse(file_get_contents($this->path));
    }
}