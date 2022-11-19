<?php

namespace MondoTalk\Core;

/**
 * Class MondoTalkConfigManager
 *
 * MondoTalkConfigManager loads the SDK configuration file and
 * hands out appropriate config params to other classes
 *
 * @package MondoTalk\Core
 */
class MondoTalkConfigManager
{

    /**
     * Configuration Options
     *
     * @var array
     */
    private $configs = array(
    );

    /**
     * Singleton Object
     *
     * @var $this
     */
    private static $instance;

    /**
     * Private Constructor
     */
    private function __construct()
    {
        if (defined('MT_CONFIG_PATH')) {
            $configFile = constant('MT_CONFIG_PATH') . '/sdk_config.ini';
        } else {
            $configFile = implode(DIRECTORY_SEPARATOR,
                array(dirname(__FILE__), "..", "config", "sdk_config.ini"));
        }
        if (file_exists($configFile)) {
            $this->addConfigFromIni($configFile);
        }
    }

    /**
     * Returns the singleton object
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add Configuration from configuration.ini files
     *
     * @param string $fileName
     * @return $this
     */
    public function addConfigFromIni($fileName)
    {
        if ($configs = parse_ini_file($fileName)) {
            $this->addConfigs($configs);
        }
        return $this;
    }

    /**
     * If a configuration exists in both arrays,
     * then the element from the first array will be used and
     * the matching key's element from the second array will be ignored.
     *
     * @param array $configs
     * @return $this
     */
    public function addConfigs($configs = array())
    {
        $this->configs = $configs + $this->configs;
        return $this;
    }

}
