/**
 * Created by mike hu on 2016/3/16.
 */

//左侧栏切换
//$("#left .menuson li").click(function () {
//    console.log($(this));
//    //$(this).addClass("leftCurrent");
//    //$("#left .menuson li").removeClass("leftCurrent");
//});
//
//$('#left .title').click(function () {
//    var $ul = $(this).next('ul');
//    $('dd').find('ul').slideUp();
//    if ($ul.is(':visible')) {
//        $(this).next('ul').slideUp();
//    } else {
//        $(this).next('ul').slideDown();
//    }
//});

//选择框样式
$(document).ready(function (e) {
    $(".select1").uedSelect({
        width: 200
    });
    $(".select2").uedSelect({
        width: 167
    });
    $(".select3").uedSelect({
        width: 100
    });
});

//提交员工查询
$('#staffList_btn_search').click(function () {
    var name = $('#name').val();
    var department = $('#department').val();
    location.href = "staffList?name=" + name + '&department=' + department;

});
//验证电话号码
//验证规则：区号+号码，区号以0开头，3位或4位
//号码由7位或8位数字组成
//区号与号码之间可以无连接符，也可以“-”连接
function checkPhone(str){
    var re = /^0\d{2,3}-?\d{7,8}$/;
    if(re.test(str)){
        return true;
    }else{
        return false;
    }
}
//验证手机号码
//验证规则：11位数字，以1开头。
function checkMobile(str) {
    var re = /^1\d{10}$/
    if (re.test(str)) {
       return true;
    } else {
       return false;
    }
}
//添加员工
$("#btn_addStaff").click( function () {
    var name = $("#name").val();
    if(name == ''){
        layer.tips('姓名不能为空', '#name', {
            tips: [1, '#3595CC'],
            time: 1000
        });
        return;
    }
    var staff_id = $("#staff_id").val();
    if(staff_id == ''){
        layer.tips('工号不能为空', '#staff_id', {
            tips: [1, '#3595CC'],
            time: 1000
        });
        return;
    }
    var gender = $("input[name='gender']:checked").val();
    var department = $("#department option:selected").text();
    //var position = $("#position option:selected").text();
    //var level = $("#level option:selected").text();
    var annul_holidays = $("#annul_holidays").val();
    var points = $("#points").val();
    var entry_date = $("#entry_date").val();
    if(entry_date == ''){
        layer.tips('入职日期不能为空', '#entry_date', {
            tips: [1, '#3595CC'],
            time: 1000
        });
        return;
    }
    var telephone = $("#telephone").val();
    if (telephone != ''){
        if(checkMobile(telephone) == false && checkPhone(telephone)==false){
            layer.tips('联系电话格式不正确', '#telephone', {
                tips: [1, '#3595CC'],
                time: 1000
            });
            return;
        }
    }
    $.ajax({
        type: "POST",
        url: "doAddStaff",
        data:{
            'name':name,
            'staff_id':staff_id,
            'gender':gender,
            'department':department,
            'annul_holidays':annul_holidays,
            'points':points,
            'entry_date':entry_date,
            'telephone':telephone
        },
        dataType:'json',
        success: function(data){
            if(data.error == 0){
                layer.msg(data.msg,{
                    time:2000 //1s后关闭
                });
                location.href = "addStaff";
            }else{
                layer.msg(data.msg,{
                    time:1000 //1s后关闭
                });
            }
        },
        error: function(){
            layer.msg('服务器忙，请稍候再试!',{
                time:2000
            });
        }
    });
});

$("#cancel_btn_addStaff").click(function(){
    location.href = "addStaff";
});

//修改员工
$("#btn_updateStaff").click( function () {
    var id = $("#id").val();
    var name = $("#name").val();
    if(name == ''){
        layer.tips('姓名不能为空', '#name', {
            tips: [1, '#3595CC'],
            time: 1000
        });
        return;
    }
    var staff_id = $("#staff_id").val();
    if(staff_id == ''){
        layer.tips('工号不能为空', '#staff_id', {
            tips: [1, '#3595CC'],
            time: 1000
        });
        return;
    }
    var gender = $("input[name='gender']:checked").val();
    var department = $("#department option:selected").text();
    //var position = $("#position option:selected").text();
    //var level = $("#level option:selected").text();
    var annul_holidays = $("#annul_holidays").val();
    var points = $("#points").val();
    var entry_date = $("#entry_date").val();
    var page = $("#page").val();
    var telephone = $("#telephone").val();

    if (telephone != ''){
        if(checkMobile(telephone) == false && checkPhone(telephone)==false){
            layer.tips('联系电话格式不正确', '#telephone', {
                tips: [1, '#3595CC'],
                time: 1000
            });
            return;
        }
    }

    $.ajax({
        type: "POST",
        url: "doUpdateStaff",
        data:{
            'id':id,
            'name':name,
            'staff_id':staff_id,
            'gender':gender,
            'department':department,
            'annul_holidays':annul_holidays,
            'points':points,
            'entry_date':entry_date,
            'telephone':telephone
        },
        dataType:'json',
        success: function(data){
            console.log(data);
            if(data.error == 0){
                location.href = "staffList?page="+page;
            }else{
                alert("员工修改失败，请稍候再试!");
            }
        },
        error: function(){
            layer.msg('服务器忙，请稍候再试!',{
                time:2000
            });
        }
    });
});

$("#cancel_btn_updateStaff").click(function(){
    location.href = "staffList";
    return;
});

//考勤统计查询
$('#checkList_btn_search').click(function () {
    var staff_name = $('#checkList_name').val();
    var department = $('#checkList_department').val();
    var year_month = $('#year_month').val();
    location.href = "checkList?staff_name=" + staff_name + '&department=' + department + '&year_month=' + year_month;
    return;
});
//导出考勤数据
$('#checkList_btn_export').click(function(){
    var staff_name = $('#checkList_name').val();
    var department = $('#checkList_department').val();
    var year_month = $('#year_month').val();
    location.href = "checkListExport?staff_name=" + staff_name + '&department=' + department + '&year_month=' + year_month;
});

//重新统计考勤
$('#afresh_checkList_btn').click(function () {
    layer.confirm('重新计算考勤？',{
        title:'重要提示',
        btn:['确定','取消']
    },function(){
        var staff_name = $('#checkList_name').val();
        var department = $('#checkList_department').val();
        var year_month = $('#year_month').val();
        location.href = "checkList?staff_name=" + staff_name + '&department=' + department + '&year_month=' + year_month +'&delete='+true;
    },function(){
        //layer.msg('取消了',{
        //    time:1000
        //})
    });

});

//签到数据查询
$('#signList_btn_search').click(function () {
    var staff_name = $('#signList_name').val();
    var department = $('#signList_department').val();
    var year_month = $('#sign_year_month').val();
    location.href = "signList?staff_name=" + staff_name + '&department=' + department + '&year_month=' + year_month;

});
//签到打卡详细数据查询
$('#signDetailList_btn_search').click(function () {
    var staff_name = $('#signDetailList_name').val();
    var year_month = $('#signDetail_year_month').val();
    location.href = "signDetailList?staff_name=" + staff_name + '&year_month=' + year_month;

});

//请假数据查询
$('#leaveList_btn_search').click(function () {
    var staff_name = $('#leaveList_name').val();
    var department = $('#leaveList_department').val();
    var year_month = $('#leave_year_month').val();
    location.href = "leaveList?staff_name=" + staff_name + '&department=' + department + '&year_month=' + year_month;

});
//补签数据查询
$('#repSignList_btn_search').click(function () {
    var staff_name = $('#repSignList_name').val();
    var department = $('#repSignList_department').val();
    var year_month = $('#repSignList_year_month').val();
    location.href = "repSignList?staff_name=" + staff_name + '&department=' + department + '&year_month=' + year_month;

});
//加班数据查询
$('#overWorkList_btn_search').click(function () {
    var staff_name = $('#overWorkList_name').val();
    var department = $('#overWorkList_department').val();
    var year_month = $('#overWorkList_year_month').val();
    location.href = "overWorkList?staff_name=" + staff_name + '&department=' + department + '&year_month=' + year_month;

});

//报销数据查询
$('#expenseList_btn_search').click(function(){
    var staff_name = $('#expenseList_name').val();
    var appr_num = $('#expenseList_appr_num').val();
    var year_month = $('#expenseList_year_month').val();
    location.href = "expenseList?staff_name=" + staff_name +'&appr_num='+appr_num+ '&year_month=' + year_month;
});

//预支数据查询
$('#advanceList_btn_search').click(function(){
    var staff_name = $('#advanceList_name').val();
    var appr_num = $('#advanceList_appr_num').val();
    var year_month = $('#advanceList_year_month').val();
    location.href = "advanceList?staff_name=" + staff_name +'&appr_num='+appr_num+ '&year_month=' + year_month;

});
//固定资产数据查询
$('#fixedAssetList_btn_search').click(function(){
    var staff_name = $('#fixedAssetList_name').val();
    var appr_num = $('#fixedAssetList_appr_num').val();
    var year_month = $('#fixedAssetList_year_month').val();
    location.href = "fixedAssetList?staff_name=" + staff_name +'&appr_num='+appr_num+ '&year_month=' + year_month;

});
//办公用品数据查询
$('#officeSupplies_btn_search').click(function(){
    var staff_name = $('#officeSupplies_name').val();
    var appr_num = $('#officeSupplies_appr_num').val();
    var year_month = $('#officeSupplies_year_month').val();
    location.href = "officeSupplies?staff_name=" + staff_name +'&appr_num='+appr_num+ '&year_month=' + year_month;

});

//资产验收数据查询
$('#assetAcceptance_btn_search').click(function(){
    var staff_name = $('#assetAcceptance_name').val();
    var appr_num = $('#assetAcceptance_appr_num').val();
    var year_month = $('#assetAcceptance_year_month').val();
    location.href = "assetAcceptance?staff_name=" + staff_name +'&appr_num='+appr_num+ '&year_month=' + year_month;
});

//导出资产验收数据
$('#assetAcceptance_btn_export').click(function(){
    var staff_name = $('#assetAcceptance_name').val();
    var appr_num = $('#assetAcceptance_appr_num').val();
    var year_month = $('#assetAcceptance_year_month').val();
    location.href = "assetAcceptanceExport?staff_name=" + staff_name +'&appr_num='+appr_num+ '&year_month=' + year_month;
});

//合同数据查询
$('#contract_btn_search').click(function(){
    var staff_name = $('#contract_name').val();
    var appr_num = $('#contract_appr_num').val();
    var year_month = $('#contract_year_month').val();
    location.href = "contract?staff_name=" + staff_name +'&appr_num='+appr_num+ '&year_month=' + year_month;
});

//考勤分析查询
$('#checkAnalyse_btn_search').click(function () {
    var staff_name = $('#checkAnalyse_name').val();
    var department = $('#checkAnalyse_department').val();
    var year_month_start = $('#year_month_start').val();
    var year_month_end = $('#year_month_end').val();
    if (year_month_start > year_month_end){
        layer.tips('开始日期不能大于结束日期', '#year_month_start', {
            tips: [1, '#3595CC'],
            time: 2000
        });
        return;
    }
    $.ajax({
        type: "POST",
        url: "ajaxCheckAnalyse",
        data:{
            'staff_name':staff_name,
            'department':department,
            'year_month_start' :year_month_start,
            'year_month_end' :year_month_end
            },
        dataType:'json',
        success: function(data){
            //console.log(data);
            var seriesData = data.seriesData;
            //console.log(seriesData);
            if(data.error == 0){
                // 基于准备好的dom，初始化echarts实例
                var myChart = echarts.init(document.getElementById('main'));

                // 指定图表的配置项和数据
                option = {
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'line'        // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    legend: {
                        data:['迟到次数','请假天数','未打卡次数','未签到次数','未签退次数','加班次数','工作日加班次数','周末加班次数','节假日加班次数']
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis : [
                        {
                            type : 'category',
                            //data : ['一月','二月','三月','四月','五月','六月']
                            data : data.xAxisData
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'迟到次数',
                            type:'bar',
                            stack: '迟到次数',
                            //data:[120, 132, 101, 134, 90, 230]
                            data: seriesData.late_count
                        },
                        {
                            name:'请假天数',
                            type:'bar',
                            stack: '请假天数',
                            //data:[220, 182, 191, 234, 290, 330]
                            data: seriesData.leave_days
                        },
                        {
                            name:'未打卡次数',
                            type:'bar',
                            //data:[320, 332, 301, 334, 390, 330],
                            data: seriesData.unsign_count,
                            //markLine : {
                            //    lineStyle: {
                            //        normal: {
                            //            type: 'dashed'
                            //        }
                            //    },
                            //    data : [
                            //        [{type : 'min'}, {type : 'max'}]
                            //    ]
                            //}
                        },
                        {
                            name:'未签到',
                            type:'bar',
                            barWidth : 5,
                            stack: '未打卡次数',
                            //data:[620, 732, 701, 734, 1090, 1130]
                            data: seriesData.unsign_in_count
                        },
                        {
                            name:'未签退',
                            type:'bar',
                            barWidth : 5,
                            stack: '未打卡次数',
                            //data:[620, 732, 701, 734, 1090, 1130]
                            data: seriesData.unsign_out_count
                        },
                        {
                            name:'加班次数',
                            type:'bar',
                            //data:[320, 332, 301, 334, 390, 330],
                            data: seriesData.over_count,
                            markLine : {
                                lineStyle: {
                                    normal: {
                                        type: 'dashed'
                                    }
                                },
                                data : [
                                    [{type : 'min'}, {type : 'max'}]
                                ]
                            }
                        },
                        {
                            name:'工作日加班',
                            type:'bar',
                            barWidth : 5,
                            stack: '加班',
                            //data:[620, 732, 701, 734, 1090, 1130]
                            data: seriesData.over_normal_count
                        },
                        {
                            name:'周末加班',
                            type:'bar',
                            barWidth : 5,
                            stack: '加班',
                            //data:[550, 662, 701, 734, 1090, 1130]
                            data: seriesData.over_weekend_count
                        },
                        {
                            name:'节假日加班',
                            type:'bar',
                            barWidth : 5,
                            stack: '加班',
                            //data:[444, 62, 70, 74, 190, 130]
                            data: seriesData.over_festival_count
                        }
                    ]
                };

                // 使用刚指定的配置项和数据显示图表。
                myChart.setOption(option);

            }else{
                layer.msg(data.msg,{
                    time:1000
                });
            }
        },
        error: function(){
            layer.msg('服务器忙，请稍候再试!');
        }
    });
});


//添加用户
$("#btn_addUser").click( function () {
    var name = $("#addUser_name").val();
    if(name == ''){
        layer.msg('请填写用户名!',{
            time:1000 //1s后关闭
        });
        return;
    }
    var password = $("#addUser_password").val();
    if(password == ''){
        layer.msg('请填写密码!',{
            time:1000
        });
        return;
    }

    $.ajax({
        type: "POST",
        url: "doAddUser",
        data:{
            'name':name,
            'password':password
        },
        dataType:'json',
        success: function(data){
            if(data.error == 0){
                layer.msg(data.msg,{
                    time:1000
                });
                location.href = "userList";
            }else{
                layer.msg(data.msg,{
                    time:1000
                });
            }
        },
        error: function(){
            layer.msg('服务器忙，请稍候再试!');
        }
    });
});

$('#cancel_btn_addUser').click(function(){
    location.href = "userList";
});
//修改用户
$('#btn_updateUser').click(function(){
    var id = $('user_id').val();
    var name = $("#updateUser_name").val();
    if(name == ''){
        layer.msg('请填写用户名!',{
            time:1000 //1s后关闭
        });
        return;
    }
    var oldPassword = $("#old_password").val();
    if(oldPassword == ''){
        layer.msg('请填原密码!',{
            time:1000
        });
        return;
    }
    var new_password = $('#new_password').val();
    if(new_password == ''){
        layer.msg('请填新密码!',{
            time:1000
        });
        return;
    }

    var confirm_password = $('#confirm_password').val();
    if (new_password != confirm_password) {
        layer.msg('新密码与确认密码不匹配!',{
            time:1000
        });
        return;
    }

    $.ajax({
        type: "POST",
        url: "doUpdateUser",
        data:{
            'id':id,
            'name':name,
            'oldPassword':oldPassword,
            'new_password':new_password,
            'confirm_password':confirm_password
        },
        dataType:'json',
        success: function(data){
            if(data.error == 0){
                layer.msg(data.msg,{
                    time:1000
                });
                location.href = "userList";
            }else{
                layer.msg(data.msg,{
                    time:1000
                });
            }
        },
        error: function(){
            layer.msg('服务器忙，请稍候再试!');
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


function showWindow(title,url,width,height,scroll){
    var win_width = width?width + 'px':'500px';
    var win_height = height?height + 'px':'500px';
    s = scroll == true?'':'no';
    //console.log(url);
    layer.open({
        type: 2,
        title: title?title:'',
        fix: false,
        shadeClose: false,
        maxmin: true,
        area: [win_width, win_height],
        content: [url,s],
        scrollbar: false
    });
}

function showConfirm(url,msg){
    //console.log(url);
    var msg = msg?msg:'确定要删除吗?';
    layer.confirm(msg,function(index){
        $.ajax({
            type: 'post',
            url: url,
            cache: false,
            success: function(msg){
                layer.msg(msg.info,{shade: 0.3});
                refresh();
            },
            error: errorCallback
        });

    });
}

function closeParent(){
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
}

function ajaxSubmit(formid){
    var form = $('#'+formid);
    var url = form.attr('action');
    var data = {};
    data = form.serializeArray();
    //console.log(data);
    _submitFn(url,data);
}


function _submitFn(url,data){
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        cache: false,
        dataType:'json',
        success: okCallback,
        error: errorCallback
    });
}


function refresh(){
    setTimeout(function(){
        location.reload(true);
    },1000);
}


function okCallback(ret){
    //console.log(ret);
    if(ret.error == 1){
        parent.layer.msg(ret.msg, {shade: 0.3}); return false;
    }else if(ret.error == 0){
        parent.layer.msg(
            ret.msg,
            {shade: 0.3},
            function(){
                parent.location.reload();return true;
            }
        )
    }
}

function errorCallback(){
    layer.msg('操作失败!');return false;
}








