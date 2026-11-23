<?php

declare(strict_types=1);

use App\Core\Router;

/** @var Router $router */
$router->get('/admin/login', 'Admin\AuthController@loginForm');
$router->post('/admin/login', 'Admin\AuthController@login');
$router->post('/admin/logout', 'Admin\AuthController@logout');
$router->get('/admin', 'Admin.DashboardController@index');
$router->get('/admin/posts', 'Admin.PostController@index');
$router->get('/admin/posts/create', 'Admin.PostController@create');
$router->post('/admin/posts/create', 'Admin.PostController@store');
$router->get('/admin/posts/edit/{id}', 'Admin.PostController@edit');
$router->post('/admin/posts/edit/{id}', 'Admin.PostController@update');
$router->post('/admin/posts/delete/{id}', 'Admin.PostController@destroy');
$router->get('/admin/categories', 'Admin.CategoryController@index');
$router->get('/admin/categories/create', 'Admin.CategoryController@create');
$router->post('/admin/categories/create', 'Admin.CategoryController@store');
$router->get('/admin/categories/edit/{id}', 'Admin.CategoryController@edit');
$router->post('/admin/categories/edit/{id}', 'Admin.CategoryController@update');
$router->post('/admin/categories/delete/{id}', 'Admin.CategoryController@destroy');
$router->get('/admin/pages', 'Admin.PageController@index');
$router->get('/admin/pages/create', 'Admin.PageController@create');
$router->post('/admin/pages/create', 'Admin.PageController@store');
$router->get('/admin/pages/edit/{id}', 'Admin.PageController@edit');
$router->post('/admin/pages/edit/{id}', 'Admin.PageController@update');
$router->post('/admin/pages/delete/{id}', 'Admin.PageController@destroy');
$router->get('/admin/tools', 'Admin.ToolController@index');
$router->get('/admin/tools/create', 'Admin.ToolController@create');
$router->post('/admin/tools/create', 'Admin.ToolController@store');
$router->get('/admin/tools/edit/{id}', 'Admin.ToolController@edit');
$router->post('/admin/tools/edit/{id}', 'Admin.ToolController@update');
$router->post('/admin/tools/delete/{id}', 'Admin.ToolController@destroy');
$router->get('/admin/settings', 'Admin.SettingsController@index');
$router->post('/admin/settings', 'Admin.SettingsController@save');
$router->get('/admin/users', 'Admin.UserController@index');
$router->get('/admin/users/create', 'Admin.UserController@create');
$router->post('/admin/users/create', 'Admin.UserController@store');
$router->get('/admin/users/edit/{id}', 'Admin.UserController@edit');
$router->post('/admin/users/edit/{id}', 'Admin.UserController@update');
$router->get('/admin/profile', 'Admin.ProfileController@edit');
$router->post('/admin/profile', 'Admin.ProfileController@save');
$router->get('/admin/media', 'Admin.MediaController@index');
$router->post('/admin/media/upload', 'Admin.MediaController@upload');
$router->post('/admin/media/delete/{id}', 'Admin.MediaController@destroy');
$router->post('/admin/media/alt/{id}', 'Admin.MediaController@updateAlt');
$router->get('/admin/media/json', 'Admin.MediaController@json');
$router->get('/admin/redirects', 'Admin.RedirectController@index');
$router->get('/admin/redirects/create', 'Admin.RedirectController@create');
$router->post('/admin/redirects/create', 'Admin.RedirectController@store');
$router->get('/admin/redirects/edit/{id}', 'Admin.RedirectController@edit');
$router->post('/admin/redirects/edit/{id}', 'Admin.RedirectController@update');
$router->post('/admin/redirects/delete/{id}', 'Admin.RedirectController@destroy');
$router->post('/admin/posts/duplicate/{id}', 'Admin.PostController@duplicate');
$router->post('/admin/editor/upload-image', 'Admin.EditorController@uploadImage');