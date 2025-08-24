<?php
// 路由


class Router {
    private array $routes = []; // 规则表
    /**
     * 根据控制器名称查找并实例化控制器
     * @param string $controllerName 控制器类名
     * @param array $request 收到的请求
     * @return Controller|null
     */
    private function findAndInstantiateController(string $controllerName,array $request){
        $possibleFiles = [
            $controllerName.'.php',
            $controllerName.'Controller.php',
            ucfirst($controllerName).'.php',
            ucfirst($controllerName).'Controller.php',
            strtolower($controllerName).'.php',
            strtolower($controllerName).'Controller.php'
        ];
        foreach($possibleFiles as $filename){
            $filePath = APP_PATH_CONTROLLER.$filename;
            if(file_exists($filePath)){
                require_once $filePath;
                $className = $this->getClassNameFromFilename($filename,$controllerName);
                if(class_exists($className)){
                    if (is_subclass_of($className,'Controller')||$className=='Controller'){
                        return new $className($request);
                    }
                }
                if(class_exists($controllerName)){
                    if (is_subclass_of($controllerName, 'Controller')||$controllerName=='Controller'){
                        return new $controllerName($request);
                    }
                }
            }
        }
        return null;
    }
    /**
     * 从文件名推断类名
     * @param string $filename 文件名
     * @param string $controllerName 原始控制器名
     * @return string
     */
    private function getClassNameFromFilename(string $filename,string $controllerName){
        $baseName = pathinfo($filename,PATHINFO_FILENAME);
        if(substr($baseName,-10) == 'Controller'){
            return $baseName;
        }
        if(substr($controllerName,-10)=='Controller'){
            return $controllerName;
        }
        
        // 默认尝试添加Controller后缀
        return ucfirst($baseName).'Controller';
    }
    /**
     * 添加规则
     * @param string $method 请求方法
     * @param string $path 路径
     * @param Controller $controller 控制器对象
     * @param string $action 要调用的方法
     * @return void
     */
    public function addRoute(string $method,string $path,Controller $controller,string $action){
        array_push($this->routes,[
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
        ]);
    }
    /**
     * 加载规则
     * @param array $routes 路由规则
     * @return void
     */
    public function loadRoutes(array $routes){
        foreach($routes as $route){
            $this->addRoute(
                $route['method'] ?? 'GET',
                $route['path'],
                $route['controller'],
                $route['action'],
            );
        }
    }
    /**
     * 生成路由规则php数组
     * @param array $routeArray 路由规则表
     * @param array $request 收到的请求
     * @return array 路由规则
     */
    public function createRoutes(array $routeArray,array $request){
        $routes = [];
        $arr = $routeArray['routes'];
        foreach($arr as $obj){
            $controllerName = $obj['controller'];
            $method = $obj['method'];
            $path = $obj['path'];
            $controller = $this->findAndInstantiateController($controllerName,$request);
            $action = $obj['action'];
            if($controller!=null){
                $routes[] = [
                    'method' => strtoupper($method),
                    'path' => $path,
                    'controller' => $controller,
                    'action' => $action,
                ];
            }
        }
        return $routes;
    }
    /**
     * 扫描控制器目录获取所有可用控制器
     * @return array 可用控制器列表
     */
    public function getAvailableControllers(): array {
        $controllers = [];
        if (is_dir(APP_PATH_CONTROLLER)){
            $files = scandir(APP_PATH_CONTROLLER);
            foreach ($files as $file){
                if (pathinfo($file,PATHINFO_EXTENSION)=='php'){
                    $filePath = (string)APP_PATH_CONTROLLER.$file;
                    require_once $filePath;
                    $className = pathinfo($file,PATHINFO_FILENAME);
                    if(class_exists($className)){
                        if(is_subclass_of($className,'Controller')||$className=='Controller'){
                            $controllers[] = [
                                'name' => $className,
                                'file' => $file,
                                'methods' => get_class_methods($className)
                            ];
                        }
                    }
                }
            }
        }
        return $controllers;
    }
    /**
     * 执行规则方法
     * @param array $request 收到的请求
     * @return int|string|mixed 控制器执行结果
     */
    public function runRouteAction(array $request){
        $method = $request['method'];
        $uri = $request['uri'];
        foreach($this->routes as $route){
            if($route['method']!=$method){continue;}
            if(match_route_path($route['path'],$uri)){
                if(method_exists($route['controller'],$route['action'])){
                    return $route['controller']->{$route['action']}($request);
                }else{
                    return "PINKCANDY FurPHP: {$route['action']} run failed.";
                }
            }
        }
        return "PINKCANDY FurPHP: route not found.";
    }
}
