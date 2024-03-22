<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Rrim\PhpUserManagement\App\Router;
use Rrim\PhpUserManagement\Controller\HomeController;
use Rrim\PhpUserManagement\Controller\UserController;
use Rrim\PhpUserManagement\Config\Database;
use Rrim\PhpUserManagement\Middleware\SessionHasFalse;
use Rrim\PhpUserManagement\Middleware\sessionHasTrue;

Database::getConnection("prod");



Router::add('GET','/',HomeController::class,'index');
Router::add('GET',"/users/register",UserController::class,'register',[sessionHasTrue::class]);
Router::add('POST',"/users/register",UserController::class,'postRegister',[sessionHasTrue::class]);
Router::add('GET',"/users/login",UserController::class,'login',[sessionHasTrue::class]);
Router::add('POST',"/users/login",UserController::class,'postLogin',[sessionHasTrue::class]);
Router::add('GET','/users/logout',UserController::class,'logout',[SessionHasFalse::class]);
Router::add('GET','/users/profile',UserController::class,'profile',[SessionHasFalse::class]);
Router::add('POST','/users/profile',UserController::class,'postProfile',[SessionHasFalse::class]);
Router::add('GET','/users/password',UserController::class,'updatePassword',[SessionHasFalse::class]);
Router::add('POST','/users/password',UserController::class,'postUpdatePassword',[SessionHasFalse::class]);

Router::run();