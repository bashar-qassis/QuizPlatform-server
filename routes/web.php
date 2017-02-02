<?php

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

use App\Quiz;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix'  =>  'admin'], function (){
    Route::get('/',                      'AdminHomeController@index');
    Route::get('login',                     'AdminAuth\LoginController@showLoginForm');
    Route::post('login',                    'AdminAuth\LoginController@login');
    Route::post('logout',                   'AdminAuth\LoginController@logout');
    Route::post('password/email',           'AdminAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset',            'AdminAuth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/reset',           'AdminAuth\ResetPasswordController@reset');
    Route::get('password/reset/{token}',    'AdminAuth\ResetPasswordController@showResetForm');
//    Route::get('register',                  'AdminAuth\RegisterController@showRegistrationForm');
//    Route::post('register',                 'AdminAuth\RegisterController@register');

    Route::resource('quizzes',              'Quizzes\QuizzesController',        ['except'   => ['create', 'edit']]);
    Route::resource('questions',            'Questions\QuestionsController',    ['except'   => ['create', 'edit']]);
    Route::resource('answers',              'Answers\AnswersController');
});


Route::get('/home', 'HomeController@index');
Route::get('{id}/quizzes',   'UserController@getQuizzes');
Route::post('{id}/quizzes/{quizId}', 'Quizzes\QuizzesController@submitSolution');

Route::get('test', function()
{
    $quiz = Quiz::findOrFail(8);
    if(strtotime($quiz->end_time) <= strtotime(\Carbon\Carbon::now())) {
        return Response::json("Submittion Rejected! Quiz has ended!",406);
    }
    return Response::json("Successful");
});