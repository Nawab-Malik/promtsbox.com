<?php

declare(strict_types=1);

use App\Core\Router;

/** @var Router $router */
$router->get('/', 'HomeController@index');
$router->get('/categories', 'CategoriesController@index');
$router->get('/category/{slug}', 'CategoryController@show');
$router->get('/blog', 'BlogController@index');
$router->get('/blog/{slug}', 'PostController@show');
$router->get('/tools', 'ToolController@index');
$router->get('/tools/{slug}', 'ToolController@show');
$router->post('/tools/{slug}/generate', 'ToolController@generate');
$router->get('/page/{slug}', 'PageController@show');
$router->post('/page/contact', 'PageController@contactSubmit');
$router->get('/search', 'SearchController@index');
$router->get('/sitemap.xml', 'SeoController@sitemap');
$router->get('/feed.xml', 'SeoController@rss');
$router->get('/robots.txt', 'SeoController@robots');