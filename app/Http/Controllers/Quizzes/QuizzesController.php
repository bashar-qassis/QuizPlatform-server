<?php

namespace App\Http\Controllers\Quizzes;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Question;
use App\Quiz;
use App\User;
use Carbon\Carbon;
use Exception;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Response;

class QuizzesController extends Controller
{
    public function __construct()
    {
//        $this->middleware('admin.user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $quizzes = Quiz::all();
        return \Response::json($quizzes);
    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $inputs = $request->only(['title', 'start_time', 'end_time', 'Questions', 'users']);
        $quiz = Quiz::create($inputs);
        $quiz->users()->attach(array_get($inputs,'users',null));
        $quiz->save();

        foreach ($inputs['Questions'] as $input)
        {
            $question = $quiz->questions()->create($input);
            $question->save();
            foreach ($input['Answers'] as $answer)
            {
                $temp = $question->answers()->create($answer);
                $temp->save();
            }
        }

        return \Response::json($quiz);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $quiz = Quiz::findOrFail($id);
        return \Response::json($quiz);
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $quiz = Quiz::findOrFail($id);

        foreach ($inputs['Questions'] as $input)
        {
            $question = Question::findOrFail($input['id']);
            $question->save();
            foreach ($input['Answers'] as $answer)
            {
                $temp = $question->answers()->create($answer);
                $temp->save();
            }
        }
        return Response::json($quiz);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        try {
            $quiz->delete();
            return Response::json($quiz);
        } catch (Exception $e) {
            return Response::json('Destroy Failed',500);
        }
    }

    /**
     * POST /userId/quizzes/quizId
     * Submit a Quiz Solution From A User
     *
     * @param $id
     * @param $quizId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitSolution($id, $quizId, Request $request)
    {
        $quiz = Quiz::findOrFail($quizId);
        if(strtotime($quiz->end_time) <= strtotime(\Carbon\Carbon::now())) {
            return Response::json("Submittion Rejected! Quiz has ended!",406);
        }
        \Log::info("Submittion: UserID= ".$id." , QuizId= " . $quizId." , Request= ".$request);
        $user = User::findOrFail($id);
        $inputs = $request->all();
//        \Log::error("Check this -> ".$inputs);
        $result = 0;
        foreach ($inputs['Questions'] as $data)
        {
            $question = Question::findOrFail($data['question_id']);
            if($question->userAnswers->find($id)) {
                return Response::json("Submittion Rejected! Already Solved!",406);
            }
            $answer = Answer::findOrFail($data['answer_id']);
            $question->userAnswers()->attach($user->id, ["answer_id" => $answer->id]);
            if($answer->is_right)
            {
                $result += 1;
            }
        }
        $user->quizzes()->updateExistingPivot($quizId, ['submitted_at' => Carbon::now()]);
        return \Response::json(['Result' => $result.'/'.count($inputs['Questions'])]);
    }
}
