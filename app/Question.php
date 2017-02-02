<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;

class Question extends \Eloquent
{
    protected $fillable = ['text', 'quiz_id'];

    public function quiz()
    {
        return $this->belongsTo('App\Quiz');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function userAnswers()
    {
        return $this->belongsToMany('App\User','answer_question_user')->withPivot('answer_id')->withTimestamps();
    }

    public function answeredBy()
    {
        return $this->belongsToMany('App\User','answer_question_user')->withPivot('answer_id')->withTimestamps();
    }

    public function toArray($options = [])
    {
        $fractal = new Manager();
        $fractal->setSerializer(new DataArraySerializer());
        // choose the defined transformer
        $transformer = $this->questionTransformer();

        // send transformer to Fractal
        $resource = new Item($this, $transformer, 'question');
        return current($fractal->createData($resource)->toArray());
    }

    public function questionTransformer()
    {
        return function(Question $question) {
            return [
                'id'        => (int) $question->id,
                'question'  => $question->text,
                'answers'   => $question->answers()->getResults(),
            ];
        };
    }
}
