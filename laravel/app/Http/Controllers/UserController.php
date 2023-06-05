<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(){
        return view('login');
    }

    public function loginpost(Request $req){
        //-- 로그사용법 (시작) | 확인은 .env파일 daily설정하면 storage->logs 안에 날짜별로 파일생성됨
        Log::debug("Validator Start", $req->only('email','password'));
        // 1.유효성 체크
        $validate = Validator::make($req->only('email','password'), [
            'email' => 'required|email|max:30'
            ,'password' => 'required|max:30|min:1'
        ]);
        Log::debug("Validator End");
        
        if($validate->fails()){
            Log::debug("Validator fails Start");

            return redirect()->back()->withErrors($validate);
        }

        // 2. 유저 정보 습득
        $user = DB::select('select id, email, password from users where email = ?', [
            $req->email
        ]);
        // if ($user || !Hash::check($req->password === $user[0]->password))
        if (!$user || !($req->password === $user[0]->password))
        {
            return redirect()->back()->withErrors(['아이디와 비밀번호를 확인해 주세요', '아이디는맞고 비번이 틀린경우']);
        }

        Log::debug("Select user", [$user[0]]);

        // 3. 유저 인증 작업(로그인)
        Auth::loginUsingId($user[0]->id);
        if(!Auth::check()){
            Log::debug("유저인증 NG");
            return redirect()->back()->withErrors('인증처리 에러');
        } else {
            Log::debug("유저인증 OK");
            return redirect('/');
        }
    }
}
