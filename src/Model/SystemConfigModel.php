<?php
class SystemConfig extends MyModel {

    protected $table = 'system_config';
    const CACHE_KEY = 'System:Configs';
    
    static $configs = [];

    public static function get($code){
        if(!empty(self::$configs) && isset(self::$configs[$code])){
            return self::$configs[$code];
        }

        $configs = Cache::get(self::CACHE_KEY);
        if(!$configs){
            $configs       = self::all();
            $configs       = array_reindex($configs, 'code', true);
            self::$configs = $configs;

            Cache::forever(self::CACHE_KEY, $configs);
        }
        return isset($configs[$code]) ? $configs[$code]->value : null;
    }

    public function renewCache(){
        return Cache::forget(self::CACHE_KEY);
    }
}