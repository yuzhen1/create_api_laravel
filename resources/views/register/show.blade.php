<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>展示</title>
    <script src="/js/jquery-3.2.1.min.js"></script>
</head>
<body>

    <table border="1">
        <tr>
            <td>APPID</td>
            <td>企业名称</td>
            <td>法人</td>
            <td>税务号</td>
            <td>营业执照</td>
            <td>对公账号</td>
            <td>当前状态</td>
            <td>操作</td>
        </tr>
        @foreach($data as $k=>$v)
        <tr id="{{$v->user_id}}">
            <td>{{$v->app_id}}</td>
            <td>{{$v->legal_person}}</td>
            <td>{{$v->tax_no}}</td>
            <td>{{$v->foreign_num}}</td>
            <td><img src="/uploads/{{$v->business_license}}" height="50px;"></td>
            <td>{{$v->firm_name}}</td>
            <td>
                @if($v->status==2)
                    审核通过
                @else
                    审核中
                @endif
            </td>

            <td>
                <input type="button" id="ok" value="审核通过">
                <input type="button" id="no" value="驳回">
            </td>
        </tr>
        @endforeach
    </table>
    <script>
        $(function(){
            //点击审核通过
            $(document).on('click','#ok',function(){
                var user_id = $(this).parents('tr').attr('id');
                $.ajax({
                    url:"http://create_api.1809a.com/admin/register/yes",
                    type:'get',
                    data:{user_id:user_id},
                    dataType:'json',
                    success:function (res) {
                        if(res.errno==0){
                            alert(res.msg);
                            window.location.reload();//刷新页面
                        }
                    }
                })
            });

            //点击驳回
            $(document).on('click','#no',function(){
                var user_id = $(this).parents('tr').attr('id');
                $.ajax({
                    url:"http://create_api.1809a.com/admin/register/no",
                    type:'get',
                    data:{user_id:user_id},
                    dataType:'json',
                    success:function (res) {
                        if(res.errno==0){
                            window.location.reload();//刷新页面
                        }
                    }
                })
            })
        })
    </script>
</body>
</html>