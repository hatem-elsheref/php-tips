<?php

namespace shefoo;

class Configuration{

    const CONFIG_DIRECTORY = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'configs';
    const ARRAY_DIRECTORY = self::CONFIG_DIRECTORY . DIRECTORY_SEPARATOR . 'arr';
    const JSON_DIRECTORY = self::CONFIG_DIRECTORY . DIRECTORY_SEPARATOR . 'json';
    const SKIPPED = ['.', '..'];

    private static $instance;
    /**
     * @var bool
     */
    private bool $jsonMode;

    /**
     * @var array
     */
    private array $systemConfiguration = [];

    /**
     * Configuration constructor.
     * @param bool $useJson
     * @param array $options
     */
    public function __construct(bool $useJson = false,array $options=[])
    {

        $this->jsonMode = $useJson;
        $useJson ? $this->loadConfigurationsFromJson() : $this->loadConfigurationsFromArray();

        if (!empty($options))
            foreach ($options as $option => $value)
                $this->setOption($option,$value);
    }

    public static function load(bool $useJson = false,array $options=[]){
        if (!self::$instance instanceof Configuration)
            self::$instance = new Configuration($useJson,$options);

       return self::$instance;
    }
    /**
     *
     */
    private function loadConfigurationsFromArray()
    {

        $configurationsFiles = array_diff(scandir(self::ARRAY_DIRECTORY), self::SKIPPED);

        $configs = [];

        array_map(function ($configFile) use (&$configs) {
            $configurations = $this->getConfigurationsFromArray($configFile);
            is_array($configurations) ? $configs[pathinfo($configFile, PATHINFO_FILENAME)] = $configurations : null;
        }, $configurationsFiles);

        $this->systemConfiguration = $configs;
    }

    /**
     *
     */
    private function loadConfigurationsFromJson()
    {

        $configurationsFiles = array_diff(scandir(self::JSON_DIRECTORY), self::SKIPPED);
        $configs = [];

        array_map(function ($configFile) use (&$configs) {
            $configurations = $this->getConfigurationsFromJson($configFile);
            is_array($configurations) ? $configs[pathinfo($configFile, PATHINFO_FILENAME)] = $configurations : null;
        }, $configurationsFiles);

        $this->systemConfiguration = $configs;
    }

    /**
     * @param string $filename
     * @return mixed
     */
    private function getConfigurationsFromArray(string $filename)
    {
        return require_once self::ARRAY_DIRECTORY . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * @param string $filename
     * @return mixed|null
     */
    private function getConfigurationsFromJson(string $filename)
    {
        $fullPath = self::JSON_DIRECTORY . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($fullPath))
            $data = file_get_contents($fullPath);
        else
            return null;

        return json_decode($data, true);
    }

    /**
     * @return array
     */
    protected function getAll()
    {
        return $this->systemConfiguration;
    }

    /**
     * @return string
     */
    protected function getMode(){
        return $this->jsonMode === true ? 'json' : 'array';
    }

    /**
     * @param string $key
     * @param null $default
     * @return array|mixed|null
     */
    protected function getOption(string $key, $default = null)
    {

        $partials = explode('.', $key);

        $neededValue = $this->systemConfiguration;

        foreach ($partials as $partial) {
            if (!isset($neededValue[$partial]))
                return $default;
            else
                $neededValue = $neededValue[$partial];
        }

        return $neededValue;
    }

    /**
     * @param string $rootKey
     * @return mixed|null
     */
    protected function getRoot(string $rootKey)
    {
        return $this->systemConfiguration[$rootKey] ?? null;
    }


    protected function setOption(string $key, $value)
    {
        $this->setNestedOption($this->systemConfiguration, $key, $value);
    }


    /**
     * @param array $context
     * @param string $option
     * @param $value
     */
    private function setNestedOption(array &$context,string $option, $value)
    {
        $partials = explode('.', $option);
        if (count($partials) === 0)
            return;
        $last = count($partials) - 1;

        for ($i = 0; $i < count($partials); $i++) {
            if ($i === $last)
                $context[$partials[$i]] = $value;
            if (!array_key_exists($partials[$i], $context)) {
                $context[$partials[$i]] = [];
            }
            $context = &$context[$partials[$i]];
        }
    }


    /**
     * @param string $key
     * @return bool
     */
    protected function hasOption(string $key){

        $partials = explode('.', $key);

        if (count($partials) === 0)
            return false;

        $configs = $this->systemConfiguration;

        foreach ($partials as $configKey){
            if (array_key_exists($configKey,$configs)){
                $configs = $configs[$configKey] ?? [];
            }else{
                return false;
            }
        }
        return true;

    }





}


