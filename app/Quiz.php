<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;

class Quiz extends \Eloquent
{
    protected $fillable = ['start_time', 'end_time', 'title'];

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function answers()
    {
        return $this->hasManyThrough('App\Answer', 'App\Question');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('submitted_at')->withTimestamps();
    }

    public function toArray($options = [])
    {
        $fractal = new Manager();
        $fractal->setSerializer(new DataArraySerializer());
        // choose the defined transformer
        $transformer = $this->quizTransformer();

        // send transformer to Fractal
        $resource = new Item($this, $transformer, 'quiz');
        return current($fractal->createData($resource)->toArray());
    }

    public function quizTransformer()
    {
        return function(Quiz $quiz) {
            return [
                'id'            => $quiz->id,
                "title"         => $quiz->title,
                'start_time'    => $quiz->start_time,
                'end_time'      => $quiz->end_time,
                'Questions'     => $quiz->questions()->getResults(),
            ];
        };
    }
}
