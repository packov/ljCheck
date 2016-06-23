<?php
namespace Home\Controller;

use Think\Controller;

set_time_limit(0);

class CheckController extends BaseController {

    public function index() {
        $this->display();
    }

    public function upload() {
        $this->display();
    }

    //考勤列表数据导出
    public function checkListExport(){
        $check = D('check');
        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $y_m = trim($_REQUEST['year_month']);
        } else {
            $y_m = date('Y-m', strtotime('-1 month'));
        }
        $where = '1 = 1';
        $where .= " AND `year_month` LIKE '{$y_m}%'";
        if (isset($_REQUEST['delete']) && $_REQUEST['delete']==true){
            $check->where($where)->delete();
        }
        $tmpCount = $check->where($where)->count();
        if ($tmpCount == 0) {
            $this->calculateCheck($y_m);
        }
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND `staff_name` LIKE '%{$staff_name}%'";
        }
        $department = trim($_REQUEST['department']);
        if ($department != null && $department != '全部') {
            $where .= " AND `department` = '{$department}'";
        } else {
            $department = '全部';
        }
        $data = $check->where($where)->field('staff_id,staff_name,department,late_time,late_count,unsign_in_count,early_time,early_count,unsign_out_count,leave_days,meal_count,over_normal_count,over_weekend_count,over_festival_count,year_month')->select();
        $filename="check_list";
        $headArr = array('工号','姓名','部门','迟到时间(分钟)','迟到次数','未签到次数','早退时间(分钟)','早退次数','未签退次数','请假天数','餐补次数','工作日加班次数','周末加班次数','节假日加班次数','日期(年-月)');
        exportExcel($filename, $headArr, $data);
    }

    //考勤列表
    public function checkList() {
        set_time_limit(0);
        $check = D('check');
        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $y_m = trim($_REQUEST['year_month']);
        } else {
            $y_m = date('Y-m', strtotime('-1 month'));
        }
        $where = '1 = 1';
        $where .= " AND `year_month` LIKE '{$y_m}%'";
        if (isset($_REQUEST['delete']) && $_REQUEST['delete']==true){
            $check->where($where)->delete();
        }
        $tmpCount = $check->where($where)->count();
        if ($tmpCount == 0) {
            $this->calculateCheck($y_m);
        }
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND `staff_name` LIKE '%{$staff_name}%'";
        }
        $department = trim($_REQUEST['department']);
        if ($department != null && $department != '全部') {
            $where .= " AND `department` = '{$department}'";
        } else {
            $department = '全部';
        }
        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $check->where($where)->count();
        $list = $check->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('department', $department);
        $this->assign('year_month', $y_m);
        $this->display();
    }

    //请假详情
    public function leaveDetails() {
        if (!isset($_REQUEST['staff_name']) || $_REQUEST['staff_name'] == null) {
            $this->error('参数错误，没有传入员工姓名!', 'Check/checkList');
        }
        $staff_name = $_REQUEST['staff_name'];
        $year_month = $_REQUEST['year_month'];
        $leave = D('leave');
        $where = '1=1';
        $where .= " AND status = '完成' AND result = '同意'" ;
        $where .= " AND people_name = '{$staff_name}' ";
        $where .= " AND start_date LIKE '{$year_month}%' ";
        $list = $leave->where($where)->select();
        $this->assign('list', $list);
        $this->display();
    }

    //请假数据
    public function leaveList() {
        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = date('Y-m', strtotime('-1 month'));
        }
        $leave = D('leave');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $department = trim($_REQUEST['department']);
        if ($department != null && $department != '全部') {
            $where .= " AND department LIKE '%{$department}%'";
        } else {
            $department = '全部';
        }
        $where .= " AND (start_date LIKE '{$year_month}%' OR end_date LIKE '{$year_month}%' ) ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $leave->where($where)->count();

        $list = $leave->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('department', $department);
        $this->assign('year_month', $year_month);
        $this->display();
    }

    //补签数据
    public function repSignList() {
        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = date('Y-m', strtotime('-1 month'));
        }
        $repSign = D('rep_sign');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $department = trim($_REQUEST['department']);
        if ($department != null && $department != '全部') {
            $where .= " AND department LIKE '%{$department}%'";
        } else {
            $department = '全部';
        }
        $where .= " AND `rep_sign_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $repSign->where($where)->count();
        $list = $repSign->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('department', $department);
        $this->assign('year_month', $year_month);
        $this->display();
    }

    //加班数据
    public function overWorkList() {
        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = date('Y-m', strtotime('-1 month'));
        }
        $overWork = D('over_work');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $department = trim($_REQUEST['department']);
        if ($department != null && $department != '全部') {
            $where .= " AND department LIKE '%{$department}%'";
        } else {
            $department = '全部';
        }
        $where .= " AND `over_start_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $overWork->where($where)->count();
        $list = $overWork->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('department', $department);
        $this->assign('year_month', $year_month);
        $this->display();

    }
    //打卡数据列表
    public function signList() {
        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = date('Y-m', strtotime('-1 month'));
        }
        $sign = D('sign');
        $where = '1=1';
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND staff_name LIKE '%{$staff_name}%' ";
        }
        $department = trim($_REQUEST['department']);
        if ($department != null && $department != '全部') {
            $where .= " AND department = '{$department}'";
        } else {
            $department = '全部';
        }
        $where .= " AND date LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $sign->where($where)->count();
        $list = $sign->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('department', $department);
        $this->assign('year_month', $year_month);
        $this->display();
    }
    //打卡详细数据列表 signDetailList
    public function signDetailList() {
        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = date('Y-m', strtotime('-1 month'));
        }
        $sign = D('sign_detail');
        $where = '1=1';
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND staff_name LIKE '%{$staff_name}%' ";
        }

        $where .= " AND `check_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $sign->where($where)->count();
        $list = $sign->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('year_month', $year_month);
        $this->display();
    }

    //考勤分析
    public function checkAnalyse() {
        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month_start']) && $_REQUEST['year_month_start'] != null) {
            $year_month_start = trim($_REQUEST['year_month_start']);
        } else {
            $year_month_start = date('Y-m', strtotime('-1 month'));
        }
        if (isset($_REQUEST['year_month_end']) && $_REQUEST['year_month_end'] != null) {
            $year_month_end = trim($_REQUEST['year_month_end']);
        } else {
            $year_month_end = date('Y-m', strtotime('-1 month'));
        }
        $staff_name = trim($_REQUEST['staff_name']);
        $department = trim($_REQUEST['department']);
        if ($department != null && $department != '全部') {
        } else {
            $department = '全部';
        }

        $this->assign('staff_name', $staff_name);
        $this->assign('department', $department);
        $this->assign('year_month_start', $year_month_start);
        $this->assign('year_month_end', $year_month_end);
        $this->display();
    }

    public function ajaxCheckAnalyse() {
        $return = array();
        $return['error'] = 0;

        //默认查询上一个月的数据
        if (isset($_REQUEST['year_month_start']) && $_REQUEST['year_month_start'] != null) {
            $year_month_start = trim($_REQUEST['year_month_start']);
        } else {
            $year_month_start = date('Y-m', strtotime('-1 month'));
        }
        if (isset($_REQUEST['year_month_end']) && $_REQUEST['year_month_end'] != null) {
            $year_month_end = trim($_REQUEST['year_month_end']);
        } else {
            $year_month_end = date('Y-m', strtotime('-1 month'));
        }

        $xAxisData = getMonthsForStartAndEnd($year_month_start,$year_month_end);
        $check = D('check');
        $where = ' 1=1 ';
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND `staff_name` LIKE '%{$staff_name}%' ";
        }
        $department = trim($_REQUEST['department']);
        if ($department != null && $department != '全部') {
            $where .= " AND `department` = '{$department}'";
        }
        $seriesData = array();
        //迟到，请假，旷工,加班，工作日加班，周末加班，节假日加班
        foreach ($xAxisData as $y_m) {
            $where_ym = "AND `year_month` = '{$y_m}'";
            $whereDo = $where . $where_ym;
            $ret = $check->where($whereDo)->field('SUM(late_count),SUM(leave_days),SUM(over_normal_count),SUM(over_weekend_count),SUM(over_festival_count),SUM(unsign_in_count),SUM(unsign_out_count)')->find();
            if (!$ret) {
                $return['error'] = 1;
                $return['msg'] = '查询失败！';
                $this->ajaxReturn($return);
            }
            $seriesData['late_count'][] = $ret['sum(late_count)'];
            $seriesData['leave_days'][] = round($ret['sum(leave_days)'], 2);
            $seriesData['over_normal_count'][] = $ret['sum(over_normal_count)'];
            $seriesData['over_weekend_count'][] = $ret['sum(over_weekend_count)'];
            $seriesData['over_festival_count'][] = $ret['sum(over_festival_count)'];
            $seriesData['over_count'][] = $ret['sum(over_normal_count)'] + $ret['sum(over_weekend_count)'] + $ret['sum(over_festival_count)'];
            $seriesData['unsign_in_count'][] = $ret['sum(unsign_in_count)'];
            $seriesData['unsign_out_count'][] = $ret['sum(unsign_out_count)'];
            $seriesData['unsign_count'][] = $ret['sum(unsign_in_count)'] + $ret['sum(unsign_out_count)'];
            $where_ym = '';
        }
        $return['xAxisData'] = $xAxisData;
        $return['seriesData'] = $seriesData;
        $this->ajaxReturn($return);
    }

    //计算-统计考勤
    public function calculateCheck($y_m) {
        //$sign = D('sign');
        $leave = D('leave');
        $repSign = D('rep_sign');
        $overWork = D('over_work');
        $staff = D('staff');
        $sign_detail = D('sign_detail');
        $check = D('check');

        //默认上班时间是09:00 如果前一天加班到11:00,第二天上班时间为10:30; (已处理)
        // 如果前一天加班到12:00,第二天上班时间为13:30; 如果前一天加班到04:00,第二天休息(补签处理);
        //上班的几个时间点类型
        $start_work_type = array(
            'type09' => '09:00:00',
            'type10' => '10:30:00',
            'type13' => '13:30:00'
        );
        //下班的几个时间点类型
        $end_work_type = array(
            'type18' => '18:00:00',
            'type21' => '21:00:00',
            'type23' => '23:00:00',
            'type24_1' => '23:59:00',
            'type24_2' => '23:59:59',
        );
        $start_work_index = $start_work_type['type09'];
        $end_work_index = $end_work_type['type18'];
        //日期分为三种类型 工作日0: normal； 周末 1：weekend；节假日2：festival
        $date_type = array('normal', 'weekend', 'festival');
        $days = getYMDofMonth($y_m);    //获取这个月每天的日期

        $array = array();
        $tmpStaff = $staff->field('name,staff_id,department')->select();
        array_unique($tmpStaff);

        $workWeekends = $this->getWorkWeekends(); //节日调休的日子，即周末日上班的日子
        foreach ($tmpStaff as $value){
            $holidays = $this->getHolidays(); //节假日

            $staffData = array();
            $staffData['staff_name'] = $value['name'];
            $staff_name = $value['name'];
            $staffData['staff_id'] = $value['staff_id'];
            $staff_id = $value['staff_id'];
            $staffData['department'] = $value['department'];
            $staffData['unsign_in_count'] = 0;
            $staffData['late_time'] = 0;
            $staffData['late_count'] = 0;
            $staffData['unsign_out_count'] = 0;
            $staffData['early_time'] = 0;
            $staffData['early_count'] = 0;
            $staffData['out_days'] = 0;
            $staffData['leave_days'] = 0;
            $staffData['meal_count'] = 0;
            $staffData['over_normal_count'] = 0;
            $staffData['over_weekend_count'] = 0;
            $staffData['over_festival_count'] = 0;

            //请假数据
            $leaveWhere = "status = '完成' AND result = '同意'";
            $leaveWhere .= " AND people_name = '{$staff_name}' ";
            $leaveWhereCount = $leaveWhere . " AND (`start_date` LIKE '{$y_m}%' OR `end_date` LIKE '{$y_m}%' )";  //计算当月总的请假天数
            $tmpDateArr = array();
            $tmpArr = $leave->where($leaveWhereCount)->select();
            if ($tmpArr == null) {
                $leave_days = 0;
            } else {
                $leave_days = 0;
                //获取请假的具体日期,每日的请假时长，上下班时间
                if ($tmpArr != null) {
                    foreach($tmpArr as $tmp){
                        $start_date = strtotime($tmp['start_date']);
                        $end_date = strtotime($tmp['end_date']);
                        //请假时长转八进制
                        $leave_date = round(myDecoct($tmp['leave_date']),1);
                        $leave_days += $leave_date;
                        $leave_days = round(myDecoct($leave_days),1);
                        $t1 = date('Y-m-d', $start_date);
                        $t2 = date('Y-m-d', $end_date);
                        $t = getDaysForStartAndEnd($t1, $t2);
                        $tCount = count($t);
                        if ($tCount == 1) {
                            $t_start_date = strtotime($t[0] . ' ' . $start_work_type['type09']);
                            $t_end_date = strtotime($t[0] . ' ' . $end_work_type['type18']);
                            if ($start_date <= $t_start_date && $end_date < $t_end_date) {   //推迟上班的请假
                                $t_start_date = $end_date;
                            }

                            if ($start_date > $t_start_date && $end_date >= $t_end_date) {   //提早下班的请假
                                $t_end_date = $start_date;
                            }
                            $tmpDateArr[$t[0]] = array('start_date' =>$t_start_date, 'end_date'=>$t_end_date, 'leave_date'=>$leave_date);
                        } elseif($tCount > 1) {
                            for($i = 0; $i < $tCount; $i++) {
                                $t_start_date = strtotime($t[$i] . ' ' . $start_work_type['type09']);
                                $t_end_date = strtotime($t[$i] . ' ' . $end_work_type['type18']);
                                $leave_date = 1;
                                if ($i == 0) {
                                    $t_end_date = $start_date;
                                    $t_hours = intval(($t_end_date - $t_start_date)/3600);
                                    if ($t_hours < 8) {
                                        $leave_date = $t_hours/10;
                                    }
                                }
                                if ($i == $tCount -1) {
                                    $t_start_date =  $end_date;
                                    $t_hours = intval(($t_end_date - $t_start_date)/3600);
                                    if ($t_hours < 8) {
                                        $leave_date = $t_hours/10;
                                    }
                                }
                                $tmpDateArr[$t[$i]] = array('start_date'=>$t_start_date, 'end_date'=>$t_end_date, 'leave_date'=>$leave_date);
                            }
                        }
                    }
                }
                // debug($tmpDateArr);
            }
            $staffData['leave_days'] = $leave_days;

            foreach($days as $day){
                $curr_date = $day;
                //特殊情况 王瑞每逢周一都是10:30上班，也就是说每个礼拜的周一，9:00-10:30这段时间不算迟到
                if ($staff_name == '王瑞' && is_monday($curr_date)) {
                    $start_work_index = $start_work_type['type10'];
                }
                $start_work_time = $curr_date .' '. $start_work_index;
                $start_work_time = strtotime($start_work_time);  //默认上班时间戳 Y-m-d 09:00,超过09:01，迟到时间从家09:00开始计算
                $end_work_time =  $curr_date . ' ' . $end_work_index;
                $end_work_time = strtotime($end_work_time);  //默认下班时间戳 Y-m-d 18:00
                $curr_date_type = $date_type[0]; //默认日期模式：工作日模式

                if (is_weekend($curr_date)) {  //周末
                    $curr_date_type = $date_type[1];  //周末模式

                    $whereSign = " `check_time` LIKE '{$curr_date}%' ";
                    $whereSign .= " AND `staff_name` = '{$staff_name}' ";
                    $whereSign_in = $whereSign . " AND `update_status` = '加班签到' ";
                    $whereSign_out = $whereSign . " AND `update_status` = '加班签退' ";

                } else{
                    $whereSign = " `check_time` LIKE '{$curr_date}%' ";
                    $whereSign .= " AND `staff_name` = '{$staff_name}' ";
                    $whereSign_in = $whereSign . " AND `update_status` = '' AND `record_status` = '正常记录' ";
                    $whereSign_out = $whereSign . " AND `update_status` = '下班签退' AND `record_status` = '正常记录' ";
                }

                //获取签到时间
                $tmparr1 = $sign_detail->where($whereSign_in)->find();
                $tmparr2 = $sign_detail->where($whereSign_out)->find();
                $sign_in_time = isset($tmparr1['check_time']) ? strtotime($tmparr1['check_time']) : null;
                $sign_out_time = isset($tmparr2['check_time']) ? strtotime($tmparr2['check_time']) : null;

                if (in_array($curr_date, $workWeekends)){
                    //节假日调休到周末的日子
                    $curr_date_type = $date_type[0];  //工作日模式
                }

                if (in_array($curr_date, $holidays)) {
                    $curr_date_type = $date_type[2]; //节假日模式

                    //判断是否申请了节假日加班
                    $overWhere = "status = '完成' AND result = '同意'";
                    $overWhere .= " AND `people_name` = '{$staff_name}' ";
                    $overWhere .= " AND `over_start_time` LIKE '{$curr_date}%'";
                    $overWork_arr = $overWork->where($overWhere)->select();
                    if ($overWork_arr == null){
                        $curr_date_type = $date_type[1]; //未申请加班相当于周末模式
                    }
                }

                //判断当天是否请假了
                if ($leave_days > 0) {
                    if (isset($tmpDateArr[$curr_date])) {
                        $leave_date = $tmpDateArr[$curr_date]['leave_date'];
                        $start_date = $tmpDateArr[$curr_date]['start_date'];
                        $end_date = $tmpDateArr[$curr_date]['end_date'];
                        if ($leave_date >= 1) {
                            $curr_date_type = $date_type[1]; //整天都请假了，相当于周末模式
                        } else{
                            $start_work_time = $start_date;
                            $end_work_time = $end_date;
                        }
                    }

                }

                //当加班时间超过24点需要进行补签，例如 2016-04-03 加班到 2016-04-04 凌晨2点 补签单应该填写补签时间是2014-04-03 02:00:00
                $time_point_0 = strtotime($curr_date . ' ' . '00:00:00');
                $time_point_4 = strtotime($curr_date . ' ' . '04:00:00');
                $time_point_6 = strtotime($curr_date . ' ' . '06:00:00');
                $time_point_12 = strtotime($curr_date . ' '. '12:00:00');

                //判断是否有补签了签到或是签退
                $repSignWhere = " `status` = '完成' AND `result` = '同意' ";
                $repSignWhere .= " AND `people_name` = '{$staff_name}' ";
                $repSignWhere .= " AND `rep_sign_time` LIKE '{$curr_date}%' ";
                $repSigns = $repSign->where($repSignWhere)->field('rep_sign_time')->select();
                $repSignTimes = array();
                if ($repSigns != null){
                    foreach ($repSigns as $repSignV){
                        if ($repSignV != null){
                            $repSignTimes[] = strtotime($repSignV['rep_sign_time']);
                        }
                    }
                }

                $repSignTimesCount = count($repSignTimes);
                if ($repSignTimesCount == 1) {
                    if($repSignTimes[0] > $time_point_6 && $repSignTimes[0] <= $time_point_12 ){
                        $sign_in_time = $repSignTimes[0];
                    }

                    if ($repSignTimes[0] > $time_point_12) {
                        $sign_out_time = $repSignTimes[0];
                    }

                    if ($repSignTimes[0] >= $time_point_0 && $repSignTimes[0] < $time_point_4) {
                        $sign_out_time = strtotime($curr_date . ' ' . $end_work_type['type24_1']);
                    }

                    if ($repSignTimes[0] >= $time_point_4 && $repSignTimes[0] <= $time_point_6) {
                        $sign_out_time = strtotime($curr_date . ' ' . $end_work_type['type24_2']);
                    }

                }

                if ($repSignTimesCount >= 2) {
                    $sign_min = min($repSignTimes);
                    $sign_max = max($repSignTimes);
                    if ($sign_min > $time_point_6 && $sign_min <= $time_point_12) {
                        $sign_in_time = $sign_min;
                        $sign_out_time = $sign_max;
                    }

                    if ($sign_min > $time_point_12) {
                        $sign_out_time = $sign_max;
                    }

                    if ($sign_min >= $time_point_0 && $sign_min < $time_point_4) {
                        $sign_out_time = strtotime($curr_date . ' ' . $end_work_type['type24_1']);
                    }

                    if ($sign_min >= $time_point_4 && $sign_min <= $time_point_6) {
                        $sign_out_time = strtotime($curr_date . ' ' . $end_work_type['type24_2']);
                    }
                }

                if ($curr_date_type == $date_type[0]){    //工作日模式
                    if ($sign_in_time == null) {
                        $staffData['unsign_in_count']++;
                    }
                    if ($sign_in_time != null && $sign_in_time > ($start_work_time+60)){
                        $staffData['late_count']++;
                        $staffData['late_time'] += periodTimeMinutes2($sign_in_time, $start_work_time);
                    }

                    if ($sign_out_time == null) {
                        $staffData['unsign_out_count']++;
                    }

                    if ($sign_out_time != null && $sign_out_time < $end_work_time) {
                        $staffData['early_count']++;
                        $staffData['early_time'] += periodTimeMinutes2($end_work_time, $sign_out_time);
                    }
                    //工作日加班超过21点，餐补,加班+1
                    if ($sign_out_time != null && $sign_out_time >= strtotime($curr_date . ' ' . $end_work_type['type21'])) {
                        $staffData['meal_count']++; //餐补
                        $staffData['over_normal_count']++;  //加班
                    }
                    $start_work_index = $start_work_type['type09'];
                    //加班时间超过晚上11点，延缓上班时间为10:30
                    if ($sign_out_time != null && $sign_out_time >= strtotime($curr_date.' '.$end_work_type['type23']) && $sign_out_time < strtotime($curr_date.''. $end_work_type['type24_1'])){
                        $start_work_index = $start_work_type['type10'];
                    }
                    //加班时间超过晚上24点，延缓上班时间为13：30
                    if ($sign_out_time != null && $sign_out_time == strtotime($curr_date .' '. $end_work_type['type24_1'])) {
                        $start_work_index = $start_work_type['type13'];
                    }
                    //加班时间到凌晨4点，延缓一天上班
                    if ($sign_out_time != null && $sign_out_time == strtotime($curr_date .' '. $end_work_type['type24_2'])) {
                        $curr_nextDay = date('Y-m-d', strtotime($curr_date ."+1 day"));
                        array_push($holidays, $curr_nextDay);
                    }

                    if ($sign_in_time == null && $sign_out_time == null) {
                        $staffData['out_days'] ++;
                    }

                }elseif($curr_date_type == $date_type[1] || $curr_date_type == $date_type[2]){
                    $proint_count = 0;
                    $meal_count = 0;
                    if ($sign_in_time != null && $sign_out_time != null){
                        $time_range = $sign_out_time - $sign_in_time;
                        if ($time_range >= (7*3600 + 45*60)) {
                            //加班大于等于8小时 积分加1，餐补加1
                            $proint_count = 1;
                            $meal_count = 1;
                        }

                        //加积分 员工表 暂不使用注释掉
//                        if ($proint_count > 0) {
//                            $staffs = $staff->where("staff_id='{$staff_id}'")->find();
//                            $points = floatval($staffs['points']) + $proint_count;
//                            $staff->points = $points;
//                            $staff->save();
//                        }

                        if ($curr_date_type == $date_type[2]) {
                            $staffData['over_festival_count']+=$proint_count;
                        } else {
                            $staffData['over_weekend_count']+=$proint_count;
                        }
                        $staffData['meal_count']+=$meal_count;
                    }
                    $start_work_index = $start_work_type['type09'];
                }

            }

            $staffData['year_month'] = $y_m;
            $staffData['update_time'] = date("Y-m-d H:i:s");
            $array[] = $staffData;
        }

        if (!$check->addAll($array)){
            $this->error('考勤统计失败!', U('Check/checkList'));
            return false;
        }
        return true;
    }

}