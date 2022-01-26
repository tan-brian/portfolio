<?php

class Session{

    static function login(){

        if(isset($_SESSION['fingerprint']) && $_SESSION['fingerprint'] === md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'])){
            return true;
        }
        else{

         return false;
        }

    }

}




 ?>
