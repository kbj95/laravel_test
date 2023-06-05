<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
{
    public function userget($email){
        $arr = [
            'code' => '0'
            ,'msg' => ''
        ];
        $user = DB::select('SELECT name, email FROM users WHERE email = ?', [$email]);

        if($user){
            $arr['code'] = '0';
            $arr['msg'] = 'Success Get User';
            $arr['data'] = $user[0];
        }
        else{
            $arr['code'] = 'E01';
            $arr['msg'] = 'No Data';
        }
        return $arr; // 요청의 결과에대해서 laravel이 자동으로 json형태로 돌려준다.
    }

    public function userpost(Request $req){
        $arr = [
            'code' => '0'
            ,'msg' => ''
        ];

        $result = DB::insert('INSERT INTO users(name, email, password) VALUES(?, ?, ?)'
        ,[
            $req->name
            ,$req->email
            ,Hash::make($req->password)
        ]);

        if($result){
            $arr['code'] = '0';
            $arr['msg'] = 'Success Registration';
            $arr['data'] = [$req->email];
        } else{
            $arr['code'] = 'E01';
            $arr['msg'] = 'Failed Registration';
        }

        return $arr;
    }

    public function userput(Request $req, $email){
        $arr = [
            'code' => '0'
            ,'msg' => ''
        ];

        $result = DB::update('UPDATE users SET name = ? WHERE email = ?'
        , [
            $req->name
            ,$email
        ]);

        if($result){
            $arr['code'] = '0';
            $arr['msg'] = 'Success Update';
            $arr['data'] = [$req->name];
        } else{
            $arr['code'] = 'E01';
            $arr['msg'] = 'Failed Update';
        }

        return $arr;
    }

    public function userdelete($email){
        $arr = [
            'code' => '0'
            ,'msg' => ''
        ];
        $date = Carbon::now();

        $result = DB::update('UPDATE users SET deleted_at = ?, del_flg = ? WHERE email = ?'
        , [
            $date
            ,'1'
            ,$email
        ]);

        if($result){
            $arr['code'] = '0';
            $arr['msg'] = 'Success Delete';
            $arr['data'] = ['deleted_at' => $date, 'email' => $email];
        } else{
            $arr['code'] = 'E01';
            $arr['msg'] = 'Failed Delete';
        }

        return $arr;
    }
}
