<?php

/*
$login = "123";
$pass = "202cb962ac59075b964b07152d234b70";
if ( ( $_SERVER['PHP_AUTH_USER'] ) != $login || md5($_SERVER['PHP_AUTH_PW']) != $pass ) {
    header ( 'WWW-Authenticate: Basic realm="authentification"' );
    header ( 'HTTP/1.0 401 Unauthorized' );
    echo "PP;Q P?Q P>P4P>P;P6P5P=P8Q Q P0P1P>QQ P=P5P>P1QP>P4P8P<P> P2P2P5QQP8 P8P<Q P?P>P;QP7P>P2P0QP5P;Q P8 P?P0Q P>P;Q.";
    exit;
}
*/
session_start();

if ((!isset($_SESSION['user_login_email']) || !isset($_SESSION['user_id'])) && Core::$controller != 'login')
    header ("Location:/admin/login");

Core::$layout="layout_01";
