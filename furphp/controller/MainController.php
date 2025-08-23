<?php
// 主控制器


class MainController extends Controller {
    public function home(){
        $html = file_get_contents(FUR_PATH_VIEW.'home.html');
        return $this->TextResponse($html);
    }
}
