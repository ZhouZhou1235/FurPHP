<?php
// 文件加载


// 全局常量
define('FUR_PATH_ROOT', str_replace('\\', '/', __DIR__.'/'));
define('FUR_PATH_CONFIG', FUR_PATH_ROOT . 'config/');
define('FUR_PATH_ASSETS', FUR_PATH_ROOT . 'assets/');
define('FUR_PATH_CONTROLLER', FUR_PATH_ROOT . 'controller/');
define('FUR_PATH_CORE', FUR_PATH_ROOT . 'core/');
define('FUR_PATH_MODEL', FUR_PATH_ROOT . 'model/');
define('FUR_PATH_VIEW', FUR_PATH_ROOT . 'view/');

// 提前加载
require_once 'core/GUtils.php';

// 自动加载器
spl_autoload_register(function($className){
    $directories = [
        FUR_PATH_ROOT,
        FUR_PATH_CORE,
        FUR_PATH_CONTROLLER,
        FUR_PATH_MODEL,
    ];
    $possibleFiles = [
        (string)$className.'.php',
        str_replace('_','/',$className).'.php',
    ];
    foreach($directories as $directory){
        foreach($possibleFiles as $filename){
            $filePath = $directory.DIRECTORY_SEPARATOR.$filename;
            if(file_exists($filePath)){
                require_once $filePath;
                return true;
            }
        }
    }
    return false;
});
