<?php

require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('view/RegisterView.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$v = new LoginView();
$dtv = new DateTimeView();
$lv = new LayoutView();
$rv = new RegisterView();

session_start();


$v->login();
if(isset($_SESSION['username'])){
    if(isset($_GET['register'])){
        $lv->render(true, $rv, $dtv);
    }else {
        $lv->render(true, $v, $dtv);
    }
}else { 
     if(isset($_GET['register'])){
        $lv->render(false, $rv, $dtv);
    }else {
        $lv->render(false, $v, $dtv);
    }
}

if(isset($_POST['LoginView::Logout'])){
    unset($_SESSION['username']);
}

