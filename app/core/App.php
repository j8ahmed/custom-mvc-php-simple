<?php

class App
{
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];
    private $url = "";

    public function __construct(){
        $this->url = $this->parseUrl();
        // print_r($this->url);
        $this->setController();
        $this->setMethod();
        $this->setParams();
        call_user_func_array([$this->controller, $this->method], $this->params);
        // print_r($this->controller);
        // print_r($this->method);
        // print_r($this->params);
    }
    private function parseUrl(){
        if(isset($_GET['url'])){
            //trim the trailing slash, santize the url, and explode its values
            return $url = explode( '/', filter_var( rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL) );
        }
    }
    private function setController(){
        if( file_exists("../app/controllers/".$this->url[0].".php") ){
            $this->controller = $this->url[0];
            unset($this->url[0]);
        }
        require_once "../app/controllers/".$this->controller.".php";
        $this->controller = new $this->controller;
    }
    private function setMethod(){
        if( isset($this->url[1]) ){
            if( method_exists($this->controller, $this->url[1]) ){
                $this->method = $this->url[1];
                unset($this->url[1]);
            }
        }
    }
    private function setParams(){
        $this->params = $this->url ? array_values($this->url) : [];
    }

}