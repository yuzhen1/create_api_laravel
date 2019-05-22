<?php

namespace App\Admin\Controllers;

use App\RegUserModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class RegUserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function show(Content $content)
    {
        $data = RegUserModel::get();
        return view('/register/show',['data'=>$data]);
    }

    //审核通过
    public function yes(){
        $id = $_GET['user_id'];
        $appid = substr(md5(time().Str::random(10)),5,15);
        $key = substr(md5(time().Str::random(10)),5,9);
        $res = RegUserModel::where(['user_id'=>$id])->update(['status'=>2,'app_id'=>$appid,'key'=>$key]);
        if($res){
           $arr = [
               'errno'=>0,
               'msg'=>'该申请通过审核'
           ];
           return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
    }
    //审核驳回
    public function no(){
        $user_id = $_GET['user_id'];
        $res = RegUserModel::where(['user_id'=>$user_id])->update(['status'=>1,'app_id'=>'','key'=>'']);
        if($res){
            $arr = [
                'errno'=>0,
                'msg'=>'已驳回'
            ];
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
    }
}
