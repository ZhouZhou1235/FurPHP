<?php
// 收发机器


class IOMachine {
    protected Config $config; // 配置
    protected Router $router; // 路由
    public function __construct(){
        $this->initMachine(
            FUR_PATH_CONFIG.'config.json',
            FUR_PATH_CONFIG.'route_table.json'
        );
    }
    /**
     * 初始化机器 
     */
    public function initMachine($configFilePath,$routeFilePath){
        $this->config = new Config($configFilePath,$routeFilePath);
        $this->router = new Router();
        $routes = $this->router->createRoutes(
            $this->config->getRouteArray(),
            $this->requestInput()
        );
        $this->router->loadRoutes($routes);
    }
    /**
     * 接收请求
     * @return array
     */
    public function requestInput(){
        return [
            'method' => $_SERVER['REQUEST_METHOD']??'GET',
            'uri' => parse_url(url:$_SERVER['REQUEST_URI']??'/',component:PHP_URL_PATH),
            'query' => $_GET,
            'params' => $_POST,
            'files' => $_FILES,
            'server' => $_SERVER,
            'cookies' => $_COOKIE,
            'session' => $_SESSION,
            'headers' => getallheaders(),
        ];
    }
    /**
     * 处理响应
     * @return int
     */
    public function process(){
        session_start();
        $request = $this->requestInput();
        $response = null;
        try{
            $response = $this->router->runRouteAction($request);
            echo $response;
            session_write_close();
            return 1;
        }
        catch(Exception $e){echo $e;return 0;}
    }
    /**
     * 获取机器的配置
     * @return Config
     */
    public function getConfig(){return $this->config;}
    /**
     * 获取机器的路由
     * @return Router
     */
    public function getRouter(){return $this->router;}
}
