<?php
namespace org\codeminus\main;

/**
 * Base controller
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
abstract class Controller {
    
    /**
     * View object
     * @var View
     */
    protected $view;

    /**
     * Base controller
     * @return Controller
     */
    public function __construct() {
        $this->view = new View();
    }
    
    /**
     * Redirects to a given location relative to APP_HTTP_PATH
     * @param string $url
     * @return void
     */
    public function redirectTo($url){
        header('Location: '.APP_HTTP_PATH.'/'.$url);
        exit;
    }
    
    /**
     * Creates a link to the given $method inside this controller
     * @param string $query you want to invoke. ex.: TestController/testMethod/arg1/arg2
     * @return string link
     */
    public static function linkTo($query){       
        return APP_HTTP_PATH.'/'.$query;
    }
    
    /**
     * Default method to be called when none is given on query string
     * @return void
     */
    abstract public function index();    
    
}