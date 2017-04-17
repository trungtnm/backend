<?php


namespace Trungtnm\Backend\Helpers;


use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YmlSettings
{
    private $settings = [];

    private $path;

    public function __construct($path)
    {
        $this->path = $path;
        try {
            $this->settings = Yaml::parse(file_get_contents($this->path));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $overwrite
     *
     * @return $this
     */
    public function writeSetting($key, $value, $overwrite = false)
    {
        return $this->_set($key, $value, $overwrite);
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
     * @return $this
     */
    private function _set($key, $value, $overwrite = true)
    {
        if (isset($this->settings[$key]) && !$overwrite) {
            $this->settings[$key] = array_merge($this->settings[$key], $value);
        } else {
            $this->settings[$key] = $value;
        }
        $this->write();

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    private function _get($key)
    {
        return isset($this->settings[$key]) ? $this->settings[$key] : null;
    }

    /**
     * Update content of parameter file
     */
    private function write()
    {
        file_put_contents($this->path, Yaml::dump($this->settings));
    }
}