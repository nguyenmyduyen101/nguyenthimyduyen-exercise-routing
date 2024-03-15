<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$users = [
    [
        'name' => 'rady',
        'posts' => ['Hello !', 'Good bye !'],
    ],
    [
        'name' => 'him',
        'posts' => ['How are you ?', 'I love mangos !'],
    ],
];

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function(){
    global $users;
    foreach ($users as $user) {
        $userNames[] = $user['name'];
    }
    return $userNames;
});

Route::get('/users', function () {
    global $users;
    $userNames = array_column($users, 'name');
    return "The users are: " . implode(', ', $userNames);
});


Route::get('/api/user', function () use ($users) {
    return response()->json($users);
});
Route::get('/api/user/{userIndex}', function ($userIndex) use ($users) {
    if (isset($users[$userIndex])) {
        return response()->json($users[$userIndex]);
    } else {
        return "Cannot find the user with index 5";
    }

})->whereNumber('userIndex');

Route::get('/api/user/{userName}', function ($userName) use ($users) {
    $user = array_filter($users, function ($user) use ($userName) {
        return $user['name'] === $userName;
    });

    if (count($user) > 0) {
        return response()->json(reset($user));
    } else {
        return "Cannot find the user with name hello";
    }
})->whereAlpha('userName');


Route::group(['prefix' => '/api/user'], function () use ($users) {
    Route::get('/{userIndex}/post/{postIndex}', function ($userIndex, $postIndex) use ($users) {
        if (isset($users[$userIndex])) {
            $user = $users[$userIndex];

            if (isset($user['posts'][$postIndex])) {
                $post = $user['posts'][$postIndex];
                return $post;
            } else {
                return "Cannot find the post with id $postIndex for user $userIndex";
            }
        } else {
            return "Cannot find the user with index $userIndex";
        }
    });
});