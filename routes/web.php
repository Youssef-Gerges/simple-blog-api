<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Laravel\Lumen\Routing\Router;


//home page
$router->get('/', 'PostsController@index');

$router->group(['prefix' => '/posts'], function (Router $router) {

    //single post
    $router->get('/{id:[0-9]+}', 'PostsController@showPost');

    //all posts of user
    $router->get('/auth/{id:[0-9]+}', 'PostsController@showAuther');

    //all posts of category
    $router->get('/cat/{id:[0-9]+}', 'PostsController@showCategoryPosts');

    $router->group(['middleware' => 'auth'], function (Router $router) {
        //add post
        $router->post('/', 'PostsController@addPost');

        //edit post
        $router->put('/', 'PostsController@editPost');

        //delete post
        $router->delete('/', 'PostsController@deletePost');
    });
});



$router->group(['prefix' => '/categories'], function (Router $router) {

    $router->get('/', 'CategoriesController@allCategories');

    $router->group(['middleware' => 'auth'], function (Router $router) {
        //add category
        $router->post('/', 'CategoriesController@addCategory');

        //edit category
        $router->put('/', 'CategoriesController@editCategory');

        //delete category
        $router->delete('/', 'CategoriesController@deleteCategory');
    });
});

$router->group(['prefix' => '/comments', 'middleware' => 'auth'], function (Router $router) {

    //add comment
    $router->post('/', 'CommentsController@addComment');

    //edit comment
    $router->put('/', 'CommentsController@editComment');

    //delete comment
    $router->delete('/', 'CommentsController@deleteComment');
});


//login user
$router->post('/login', 'AuthController@login');

//register user
$router->post('/register', 'AuthController@register');
