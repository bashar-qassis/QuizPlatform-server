<?php
namespace App\Http\Controllers;

use App\Answer;
use App\Post;
use App\Question;
use App\Quiz;
use App\User;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function postSignUp(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'first_name' => 'required|max:120',
            'password' => 'required|min:4'
        ]);

        $name = $request['first_name'];
        $email = $request['email'];
        $password = bcrypt($request['password']);

        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = $password;

        $user->save();

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function postSignIn(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request['email'];
        $password = $request['password'];

        if( Auth::attempt(['email' => $email, 'password' => $password]) ) {
            return redirect()->route('dashboard');
        }
        return redirect()->back();
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function getAccount()
    {
        return view('account', ['user' => Auth::user()]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuizzes($id)
    {
//        $quiz = Auth::user()->quizzes;
        $user = User::findOrFail($id);
        $quiz = $user->quizzes()
            ->where('end_time', '>=', Carbon::now())
            ->where('start_time', '<=', Carbon::now())
            ->where('submitted_at', NULL)
            ->get();
        return \Response::json(["quizzes" => $quiz]);
    }
}
