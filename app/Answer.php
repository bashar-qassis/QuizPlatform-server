<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;

class Answer extends \Eloquent
{
    protected $fillable = ['text', 'is_right', 'question_id'];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function toArray($options = [])
    {
        $fractal = new Manager();
        $fractal->setSerializer(new DataArraySerializer());
        // choose the defined transformer
        $transformer = $this->answerTransformer();

        // send transformer to Fractal
        $resource = new Item($this, $transformer, 'answer');
        return current($fractal->createData($resource)->toArray());
    }

    public function answerTransformer()
    {
        return function(Answer $answer) {
            return [
                'id'        => (int) $answer->id,
                'is_right'  => $answer->is_right,
                'answer'  => $answer->text,
            ];
        };
    }
}
