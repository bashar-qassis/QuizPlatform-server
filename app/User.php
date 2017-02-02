<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends \Eloquent implements AuthenticatableContract, CanResetPasswordContract
{
    use Notifiable;
    use Authenticatable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function quizzes()
    {
        return $this->belongsToMany('App\Quiz')->withPivot('submitted_at')->withTimestamps();
    }

    public function answeredQuestions()
    {
        return $this->belongsToMany('App\Question','answer_question_user')->withPivot('answer_id')->withTimestamps();
    }

    public function questionsAnswers()
    {
        return $this->belongsToMany('App\Answer','answer_question_user')->withPivot('question_id')->withTimestamps();
    }
}
