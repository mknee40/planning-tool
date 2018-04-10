<?php
session_start();

function pageCheck(){
    if(!isset($_SESSION["agency"])){
        return false;
    }
    return true;
}

?>