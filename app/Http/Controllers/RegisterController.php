<?php

namespace App\Http\Controllers;

use App\RegUserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class RegisterController extends Controller
{
    //注册
    public function reg(){
        return view('register/reg');
    }

    //注册执行
    public function reg_do(request $request){
        $data = $request->input();
        $getInfo = DB::table('reg_user')->where(['firm_name'=>$data['firm_name']])->first();
        //验证
        if($getInfo){
            die('企业名称已存在');
        }
        $data['business_license'] = $this->upload($request,'business_license');
        $res = DB::table('reg_user')->insertGetId($data);
        if($res){
//            $arr=[
//                'errno'=>0,
//                'msg'=>'注册成功,待审核'
//            ];
            echo "注册成功";
            $key = 'reg:user_id';
            Redis::set($key,$res);
            Redis::expire($key,604800);
            setcookie('user_id',$res,time()+3600*24,'/','1809a.com',false,true);
//            return json_encode($arr,JSON_UNESCAPED_UNICODE);
            header("Location:http://create_api.1809a.com/reg/firm_list?user_id=$res");
        }
    }

    //根据appid和key生成access-token
    public function create_token(){
        $key = 'reg:user_id';
//        dd( Redis::get($key));
       $data = RegUserModel::where(['user_id'=>Redis::get($key)])->first();
       $appid = $data->app_id;
       $key = $data->key;
       $url = "http://api.1809a.com/create/access_token?appid=$appid&key=$key";
        // create curl resource
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        print_r($output);
        // close curl resource to free up system resources
        curl_close($ch);
    }

    //文件上传
    public function upload(request $request,$filename){
        if ($request->hasFile($filename) && $request->file($filename)->isValid()) {
            $photo = $request->file($filename);
            $extension = $photo->extension();
            $filename=substr((time().Str::random(15)),3,18).'.'.$extension;
            $file_path='uploads/'.date('Ymd');
            $store_result = $photo->storeAs($file_path, $filename);
            return $store_result;exit;
        }
        exit('未获取到上传文件或上传过程出错');
    }

    //测试
    public function test(){
        $server=json_encode($_SERVER);
        $url='http://api.1809a.com/create/Ip?server='.$server.'&appid=3806acf80981db9&key=6d0d0c46f&access_token=0ba1d661059c218a0a56aa4b67aa9f27';
//        dd($url);
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response=curl_exec($ch);
        echo $response;
        curl_close($ch);
    }

    //企业信息展示
    public function firm_list(){
        $user_id = $_GET['user_id'];
        $where = [
            'user_id'=>$user_id
        ];
        $userInfo = RegUserModel::where($where)->first();
        if($userInfo->status==1){
            echo "待审核";
        }else{
            $data=[
                'appid'=>$userInfo->app_id,
                'key'=>$userInfo->key
            ];
            return view('register/firm_list',compact('data'));
        }
    }
}
