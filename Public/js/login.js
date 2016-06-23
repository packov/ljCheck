/**
 * Created by mike hu on 2016/3/16.
 */
//登录页
$(function () {
    $('.loginbox').css({'position': 'absolute', 'left': ($(window).width() - 692) / 2});
    $(window).resize(function () {
        $('.loginbox').css({'position': 'absolute', 'left': ($(window).width() - 692) / 2});
    })
});

document.onkeydown=function(event){
    e = event ? event :(window.event ? window.event : null);
    if(e.keyCode==13){
        //执行的方法
        $('#login_btn').click();
    }
}

$('#login_name').blur(function(){
    var name = $('#login_name').val();
    if(name == ""){
        layer.tips('请输入用户名', '#login_name', {
            tips: [1, '#3595CC'],
            time: 1000
        });
    }

});
$('#login_password').blur(function(){
    var password = $('#login_password').val();
    if (password == ""){
        layer.tips('请输入密码', '#login_password', {
            tips: [1, '#3595CC'],
            time: 1000
        });
    }
});
//登录
$('#login_btn').click(function(){
    var name = $('#login_name').val();
    var password = $('#login_password').val();
    if (name == ""){
        layer.tips('请输入用户名', '#login_name', {
            tips: [1, '#3595CC'],
            time: 1000
        });
        return false;
    }
    if (password == ""){
        layer.tips('请输入密码', '#login_password', {
            tips: [1, '#3595CC'],
            time: 1000
        });
        return false;
    }

    $.ajax({
        url:'doLogin',
        type:'post',
        data:{'name':name,'password':password},
        dataType:'json',
        error:function(){
            layer.msg('服务器繁忙，请稍后再试!',{
                time:2000
            });
        },
        success:function(data){
            //console.log(data);
             if(data.error == 0){
                 location.href = "login";
                 //location.href = data.url;
             }else{
                 layer.msg(data.msg,{
                     time:2000
                 });
             }
        }
    });
});

$('.help').click(function(){
    layer.open({
        type: 1,
        title:'帮助',
        area: ['600px', '100px'],
        shadeClose: true, //点击遮罩关闭
        content: '技术问题联系：xinghu@lingjing.com'
    });
});

$('.about').click(function(){
    layer.open({
        type: 1,
        title:'关于',
        area: ['600px', '100px'],
        shadeClose: true, //点击遮罩关闭
        content: '各部门的考勤统计与管理'
    });
});
