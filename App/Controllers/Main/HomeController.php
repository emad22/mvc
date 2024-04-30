<?php
namespace App\Controllers\Main;
use System\Controller;
class HomeController  extends Controller{
    public  function index() {
        // echo  "Hello World!";
        // echo $this->request->url();
        // $view = $this->view->render('main/home');
        // var_dump($view);
        // echo $view;
        // pre($this->db);
    //    echo $this->db->data(
    //         [
    //             'email' => 'admin@gmail.com',
    //             'first_name' => 'Admin'
    //         ]
    //     )->update('users')->lastID();
        // $this->db->query(' SELECT first_name FROM users  WHERE id > ? ', [1]);
        // $this->db->query(' UPDATE users SET email = ?  WHERE id=? ', ['Alexander@gmail.com', '13']);
        $this->db->data('email', 'alex@gmail.com')
                 ->where("id = ?" , 13)
                 ->update('users');
    //    $this->db->query(' INSERT INTO users  SET email = ? , first_name =? ' , ['admin@gmail.com' ,  'Admin']);

    }
}