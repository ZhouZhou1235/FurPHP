<?php
// 全局工具


/**
 * 匹配路由路径
 * 支持动态路由 如 /page1/:id
 * @param string $path 路径
 * @param string $uri 请求url
 * @return bool
 */
function match_route_path(string $path,string $uri){
    $pathSegments = explode('/',$path);
    $uriSegments = explode('/',$uri);
    if(count($pathSegments)!==count($uriSegments)){return false;}
    for($i=0;$i<count($pathSegments);$i++){
        $pathPart = $pathSegments[$i];
        $uriPart = $uriSegments[$i];
        if(strstr($pathPart,":")){continue;}
        if($pathPart!=$uriPart){return false;}
    }
    return true;
}

/**
 * 提供路径读取json为php值（通常为数组）
 * @param string $path json文件路径
 * @return int|array|mixed
 */
function get_json2array(string $path){
    if($s=file_get_contents($path)){
        if($arr=json_decode($s,JSON_OBJECT_AS_ARRAY)){
            return $arr;
        }
        return 0;
    }
    return 0;
}

/**
 * 扫描指定目录下所有PHP文件
 * 
 * @param string $directory 扫描目录
 * @param array &$results 存储结果的引用数组
 * @return array PHP文件路径数组
 */
function scanPhpFiles($directory,&$results=[]) {
    if(!is_dir($directory)){
        throw new InvalidArgumentException("PINKCANDY FurPHP: directory not found.");
    }
    $files = scandir($directory);
    foreach ($files as $file) {
        if($file == '.'||$file == '..'){continue;}
        $path = $directory.DIRECTORY_SEPARATOR.$file;
        if(is_dir($path)){
            scanPhpFiles($path,$results);
        }else{
            if(pathinfo($path,PATHINFO_EXTENSION)=='php'){
                $results[] = $path;
            }
        }
    }
    return $results;
}
