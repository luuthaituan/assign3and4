<?php
namespace app\controllers;
use libs\DB;
class HomeController {
    public function showPost($currentRoute) {
        $db = new DB();
        $db->table('posts');
        $posts = $db->getData();
        var_dump($currentRoute);
    }


}