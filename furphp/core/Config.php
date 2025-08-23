<?php
// 机器配置


class Config {
    private array $configArray; // 配置php数组
    private array $routeArray; // 路由规则表
    public function __construct(string $configFilePath,string $routeFilePath){
        $this->loadConfigArray($configFilePath);
        $this->loadRouteArray($routeFilePath);
    }
    /**
     * 加载配置
     * @param string $path 配置文件路径
     * @return int
     */
    public function loadConfigArray(string $path){
        $this->configArray = get_json2array($path);
        return gettype($this->configArray)=='array'?1:0;
    }
    /**
     * 加载路由规则表
     * @param string $path 路由规则文件路径
     * @return int
     */
    public function loadRouteArray(string $path){
        $this->routeArray = get_json2array($path);
        return gettype($this->routeArray)=='array'?1:0;
    }
    /**
     * 获取配置
     * @return array
     */
    public function getConfigArray(){return $this->configArray;}
    /**
     * 获取路由规则表
     * @return array
     */
    public function getRouteArray(){return $this->routeArray;}
}
