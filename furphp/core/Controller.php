<?php
// 控制器


class Controller {
    protected array $request; // 请求
    protected mixed $response; // 响应
    public function __construct(array $request){
        $this->request = $request;
        $this->response = null;
    }
    /**
     * json响应
     * @param array $data 结果php数组
     * @return string
     */
    public function JsonResponse(array $data){
        $this->response = json_encode($data,JSON_PRETTY_PRINT);
        return $this->response;
    }
    /**
     * 文本响应
     * @param string $text 结果文本
     * @return string
     */
    public function TextResponse(string $text){
        $this->response = $text;
        return $this->response;
    }
}
