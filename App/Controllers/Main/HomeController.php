<?php
namespace App\Controllers\Main;
use System\Controller;
class HomeController  extends Controller{
    public  function index() {
        // echo  "Hello World!";
        // echo $this->request->url();
        $view = $this->view->render('main/home');
        // var_dump($view);
        echo $view;
    }
}