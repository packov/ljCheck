<?php
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller {
    function __construct() {
        parent::__construct();
        $c_a = CONTROLLER_NAME.'_'. ACTION_NAME;
        $this->assign('c_a', $c_a);
        $this->assign('username', $_SESSION['username']);
        if($_SESSION['username'] == null || $_SESSION['password'] == null){
            redirect(U('Login/index'));
        }
    }

    public function _empty(){
        $this->display('Public:404');
    }

    //请假Excel表每列对应的字段
    public static $leaveMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'leave_type',      //请假类型
        'O' => 'start_date',      //开始时间
        'P' => 'end_date',       //结束时间
        'Q' => 'leave_date',     //请假天数(天)
        'R' => 'leave_reason',   //请假事由
        'S' => 'img'             //图片
    );
    //补签Excel表每列对应的字段
    public static $repSignMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'rep_sign_time' ,  //补签时间
        'O'=> 'rep_sign_type'     //补签类型
    );
    //加班表每列对应的字段
    public static $overWorkMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'over_start_time', //加班开始时间
        'O' => 'over_end_time',   //加班结束时间
        'P' => 'over_hours',       //加班时长
        'Q' => 'is_festival',     //是否法定节假日
        'R' => 'over_reason'     //加班原因
    );
    //打卡签到Excel表每列对应的字段
    public static $signMap = array(
        'B' => 'check_id',       //考勤号
        'C' => 'staff_id',      //自定义编号(工号)
        'D' => 'staff_name',    //员工姓名
        'F' => 'date',            //日期
        'H' => 'start_work_time', //上班时间 09:00
        'I' => 'end_work_time',   //下班时间 18:00
        'J' => 'sign_in_time',   //签到时间
        'K' => 'sign_out_time',  //签退时间
        'N' => 'late_time',     //迟到时间
        'O' => 'early_time',    //早退时间
        'P' => 'is_out_work',   //是否旷工
        'V' => 'department',    //部门
        'W' => 'normal',        //平时
        'X' => 'weekend',       //周末
        'Y' => 'festival'      //节假日
    );

    //打卡签到详情Excel表每列对应的字段
    public static $signDetailMap = array(
        'A'=>'check_id',   //考勤号
        'B'=>'staff_id',   //自定义编号(工号)
        'C'=>'staff_name',  //员工姓名
        'D'=>'check_time',  //出勤时间
        'E'=>'check_status', //出勤状态
        'F'=>'update_status', //更正状态
        'G'=>'record_status'  //异常情况(记录状态)
    );

    //报销Excel表每列对应的字段
    public static $expenseMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'payee_name', //收款人
        'O' => 'payee_account',   //收款人账号
        'P' => 'use_explain',       //用途说明
        'Q' => 'detail',     //报销明细
        'R' => 'detail_date',     //明细-日期
        'S' => 'detail_amount', //明细-报销金额
        'T' => 'detail_type', //明细-报销类型
        'U' => 'detail_expalin' //明细-费用说明

    );

    //预支Excel表每列对应的字段
    public static $advanceMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'payee_name', //收款人
        'O' => 'payee_account',   //收款人账号
        'P' => 'use_explain',       //用途说明
        'Q' => 'detail',     //报销明细
        'R' => 'detail_date',     //明细-日期
        'S' => 'detail_amount', //明细-预支金额
        'T' => 'detail_type', //明细-预支类型
        'U' => 'detail_expalin' //明细-费用说明
    );
    //固定资产Excel表每列对应的字段
    public static $fixedAssetMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'purchase_eplain', //采购说明
        'O' => 'purchase_type',   //采购类型
        'P' => 'date',       //期望交付日期
        'Q' => 'detail',     //采购明细
        'R' => 'detail_name',     //明细-名称
        'S' => 'detail_format', //明细-规格
        'T' => 'detail_number', //明细-数量
        'U' => 'detail_unit', //明细-单位
        'V' => 'detail_price', //明细-价格
        'W' => 'remark' //备注
    );
    //办公用品Excel表每列对应的字段
    public static $officeSuppliesMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'purchase_eplain', //采购说明
        'O' => 'purchase_type',   //采购类型
        'P' => 'date',       //期望交付日期
        'Q' => 'detail',     //采购明细
        'R' => 'detail_name',     //明细-名称
        'S' => 'detail_format', //明细-规格
        'T' => 'detail_number', //明细-数量
        'U' => 'detail_unit', //明细-单位
        'V' => 'detail_price', //明细-价格
        'W' => 'remark' //备注
    );
    //资产验收Excel表每列对应的字段
    public static $assetAcceptanceMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'purchase_num', //采购编号
        'O' => 'asset_name',   //资产名称
        'P' => 'asset_format',       //规格型号
        'Q' => 'machine_no',     //机身号
        'R' => 'purchase_depart',     //采购部门
        'S' => 'acceptance_result', //验收情况
        'T' => 'date', //日期
        'U' => 'use_depart', //领用部门
        'V' => 'use_people' //领用人
    );
    //合同审批Excel表每列对应的字段
    public static $contractMap = array(
        'A' => 'appr_num',  //审批编号
        'B' => 'title',     //标题
        'C' => 'status',    //审批状态
        'D' => 'result',    //审批结果
        'E' => 'start_time', //审批发起时间
        'F' => 'end_time',   //审批完成时间
        'G' => 'people_num', //发起人工号
        'H' => 'people_name',//发起人姓名
        'I' => 'department', //发起人部门
        'J' => 'history_appr', //历史审批人姓名
        'K' => 'appr_record',     //审批记录
        'L' => 'curr_manager',   //当前处理人姓名
        'M' => 'time_consuming',  //审批耗时
        'N' => 'contract_num', //合同编号
        'O' => 'contract_type',   //合同类型
        'P' => 'party_a',       //甲方
        'Q' => 'party_a_addr',     //甲方地址
        'R' => 'party_b',     //乙方
        'S' => 'party_b_addr', //乙方地址
        'T' => 'amount', //标的金额
        'U' => 'capital_amount', //标的金额大写
        'V' => 'attachment', //附件
        'W' => 'remark' //备注
    );


    public function index() {
        $this->assign('username', $_SESSION['username']);
    }

    //获取节假日(工作日不需要上班的日子)
    public function getHolidays() {
        $holidays = D('cfg_holidays');
        $data = $holidays->field('holiday')->select();
        $array = array();
        if ($data != null) {
            foreach ($data as $v) {
                $array[] = $v['holiday'];
            }
        }
        return $array;
    }

    //获取周末调休(周末需要上班的日子)
    public function getWorkWeekends() {
        $work_weekend = D('cfg_work_weekend');
        $data = $work_weekend->field('work_weekend')->select();
        $array = array();
        if ($data != null) {
            foreach ($data as $v) {
                $array[] = $v['work_weekend'];
            }
        }
        return $array;
    }

    public function doUploadExcel() {
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('xls', 'xlsx');// 设置附件上传类型
        //$upload->rootPath = './Public/Uploads/';
        $upload->rootPath = '.';
        $upload->savePath  = '/Public/uploads/';// 设置附件上传目录
        //debug($_FILES);
        // 上传文件
        $tableName = 'sign';
        if (isset($_FILES['sign_excel']) && $_FILES['sign_excel'] != null) {
            $info = $upload->uploadOne($_FILES['sign_excel']);
        } elseif (isset($_FILES['leave_excel']) && $_FILES['leave_excel'] != null) {
            $info = $upload->uploadOne($_FILES['leave_excel']);
            $tableName = 'leave';
        } elseif (isset($_FILES['rep_sign_excel']) && $_FILES['rep_sign_excel'] != null) {
            $info = $upload->uploadOne($_FILES['rep_sign_excel']);
            $tableName = 'rep_sign';
        } elseif (isset($_FILES['over_work_excel']) && $_FILES['over_work_excel'] != null) {
            $info = $upload->uploadOne($_FILES['over_work_excel']);
            $tableName = 'over_work';
        } elseif (isset($_FILES['sign_detail_excel']) && $_FILES['sign_detail_excel'] != null) {
            $info = $upload->uploadOne($_FILES['sign_detail_excel']);
            $tableName = 'sign_detail';
        } elseif (isset($_FILES['expense_excel']) && $_FILES['expense_excel'] != null) {
            $info = $upload->uploadOne($_FILES['expense_excel']);
            $tableName = 'expense';
        } elseif (isset($_FILES['advance_excel']) && $_FILES['advance_excel'] != null) {
            $info = $upload->uploadOne($_FILES['advance_excel']);
            $tableName = 'advance';
        } elseif (isset($_FILES['fixed_asset_excel']) && $_FILES['fixed_asset_excel'] != null) {
            $info = $upload->uploadOne($_FILES['fixed_asset_excel']);
            $tableName = 'fixed_asset';
        } elseif (isset($_FILES['office_supplies']) && $_FILES['office_supplies'] != null) {
            $info = $upload->uploadOne($_FILES['office_supplies']);
            $tableName = 'office_supplies';
        } elseif (isset($_FILES['asset_acceptance']) && $_FILES['asset_acceptance'] != null) {
            $info = $upload->uploadOne($_FILES['asset_acceptance']);
            $tableName = 'asset_acceptance';
        } elseif (isset($_FILES['contract']) && $_FILES['contract'] != null) {
            $info = $upload->uploadOne($_FILES['contract']);
            $tableName = 'contract';
        }

        $exts = $info['ext'];
        $filename = $upload->rootPath . $info['savepath'] . $info['savename'];

        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {
            // 上传成功 获取上传文件信息
            $this->loadExcelData($filename, $exts, $tableName);
        }
    }

    //导入excel数据
    public function loadExcelData($filename, $exts = 'xls', $tableName = 'sign') {
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
        import("Org.Util.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel = new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        if ($exts == 'xls') {
            import("Org.Util.PHPExcel.Reader.Excel5");
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } else if ($exts == 'xlsx') {
            import("Org.Util.PHPExcel.Reader.Excel2007");
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        }
        import("Org.Util.PHPExcel.IOFactory.php");

        //载入文件
        $PHPExcel = $PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $sheetCount = $PHPExcel->getSheetCount();
        //debug($sheetCount);

        for($i = 0; $i < $sheetCount; $i++){
            $arr = array();
            $currentSheet = $PHPExcel->getSheet($i);
            //获取总列数
            $allColumn = $currentSheet->getHighestColumn();
            //获取总行数
            $allRow = $currentSheet->getHighestRow();
            //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
            if ($tableName == 'sign') {
                $allColumn_toNum = \PHPExcel_Cell::columnIndexFromString($allColumn);
                $Column_AtoNum = \PHPExcel_Cell::columnIndexFromString('A');

                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                    for ($currentColumn = $Column_AtoNum; $currentColumn <= $allColumn_toNum; $currentColumn++) {
                        //数据坐标
                        $currentColumn2 = \PHPExcel_Cell::stringFromColumnIndex($currentColumn);
                        $address = $currentColumn2 . $currentRow;
                        //读取到的数据，保存到数组$arr中
                        $cell = $currentSheet->getCell($address)->getValue();

                        if ($cell instanceof PHPExcel_RichText) {
                            $cell = $cell->__toString();
                        }
                        if (isset(self::$signMap[$currentColumn2])) {
                            $arr[$currentRow][self::$signMap[$currentColumn2]] = $cell;
                        }
                    }
                }
            } else {
                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                    //从哪列开始，A表示第一列
                    for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                        //数据坐标
                        $address = $currentColumn . $currentRow;
                        //读取到的数据，保存到数组$arr中
                        $cell = $currentSheet->getCell($address)->getValue();
                        //$cell = $data[$currentRow][$currentColumn];
                        if ($cell instanceof PHPExcel_RichText) {
                            $cell = $cell->__toString();
                        }
                        if ($tableName == 'rep_sign') {
                            $arr[$currentRow][self::$repSignMap[$currentColumn]] = $cell;
                        } elseif ($tableName == 'over_work') {
                            $arr[$currentRow][self::$overWorkMap[$currentColumn]] = $cell;
                        } elseif($tableName == 'leave') {
                            $arr[$currentRow][self::$leaveMap[$currentColumn]] = $cell;
                        } elseif($tableName == 'sign_detail') {
                            $arr[$currentRow][self::$signDetailMap[$currentColumn]] = $cell;
                        } elseif($tableName == 'expense'){
                            $arr[$currentRow][self::$expenseMap[$currentColumn]] = $cell;
                        } elseif($tableName == 'advance') {
                            $arr[$currentRow][self::$advanceMap[$currentColumn]] = $cell;
                        } elseif($tableName == 'fixed_asset') {
                            $arr[$currentRow][self::$fixedAssetMap[$currentColumn]] = $cell;
                        } elseif($tableName == 'office_supplies') {
                            $arr[$currentRow][self::$officeSuppliesMap[$currentColumn]] = $cell;
                        } elseif($tableName == 'asset_acceptance') {
                            $arr[$currentRow][self::$assetAcceptanceMap[$currentColumn]] = $cell;
                        } elseif($tableName == 'contract') {
                            $arr[$currentRow][self::$contractMap[$currentColumn]] = $cell;
                        }

                    }
                }
            }
            $this->saveExcelData($arr, $tableName);
        }

    }

    //保存导入excel数据，入库
    public function saveExcelData($arr, $tableName = 'sign') {
        $table = D($tableName);
        $load_time = date('Y-m-d H:i:s', time());
        $array = array();
        $staff = D('staff');
        foreach ($arr as $key => $value) {
            //判断这条记录是否保存过
            if ($tableName == 'sign') {
                $staff_name = $value['staff_name'];
                $date = $value['date'];
                if ($table->where("`staff_name`='{$staff_name}' AND `date`='{$date}'")->select()) {
                    continue;
                }
            } elseif($tableName == 'sign_detail') {
                $staff_name = $value['staff_name'];
                $check_time = $value['check_time'];
                if ($table->where("`staff_name`='{$staff_name}' AND `check_time`='{$check_time}'")->select()) {
                    continue;
                }
            } elseif($tableName == 'leave'){
                $appr_num = $value['appr_num'];
                if ($table->where("`appr_num`='{$appr_num}'")->select()) {
                    continue;
                }else{
                    //年假病假的最小请假单位是半天(0.4天)
                    if($value['leave_type'] == '年假' || $value['leave_type'] == '病假') {
                        if ($value['leave_date'] <= 0.4) {
                            $value['leave_date'] = 0.4;
                        }
                    }
                    //如果是请了年假 扣除年假
                    if($value['leave_type'] == '年假' && $value['status'] == '完成' && $value['result'] == '同意'){
                        $staff_name = $value['people_name'];
                        $leave_date = $value['leave_date'];
                        $staffs = $staff->where("name='{$staff_name}'")->find();
                        $annul_holidays = floatval($staffs['annul_holidays']) - $leave_date;
                        $staff->annul_holidays = $annul_holidays;
                        $staff->save();
                    }
                }
            } elseif($tableName == 'expense' || $tableName == 'advance' || $tableName == 'fixed_asset' || $tableName == 'office_supplies'){
                $appr_num = $value['appr_num'];
                $detail = $value['detail'];
                $where = "`appr_num`='{$appr_num}' AND `detail`='{$detail}'";
                if ($table->where($where)->select()) {
                    continue;
                }
            }else {
                $appr_num = $value['appr_num'];
                if ($table->where("`appr_num`='{$appr_num}'")->select()) {
                    continue;
                }
            }
            $value['load_time'] = $load_time;
            $array[] = $value;
        }
        $result = $table->addAll($array);
        if (false !== $result || 0 !== $result) {
            $this->success('导入数据成功');
        } else {
            $this->error('导入数据失败');
        }

    }

    public function edit(){
        $db_name = CONTROLLER_NAME;
        $model   = M( $db_name );
        $id      = I( $model->getPk() );
        $vo      = $model->find( $id );
        //debug($vo);
        $this->assign( 'vo', $vo );
        $this->display();
    }

    public function add(){
        $this->display();
    }

    public function del(){
        if($_SESSION['username']!='admin'){
            $this->error('非admin用户,没有删除权限!');
        }
        $db_name  = CONTROLLER_NAME;
        $model  = D($db_name);
        if(!empty($model)){
            $pk = $model->getPk();
            $id = $_REQUEST[$pk];

            if(isset($id)){
                $condition = array(
                    $pk => array('in', explode( ',', $id ))
                );
                if(false !== $model->where($condition)->delete()){
                    $this->success(L('_OPERATION_SUCCESS_'));
                }else{
                    $this->error(L('_OPERATION_FAIL_'));
                }
            }else{
                $this->error(L('_ERROR_ACTION_'));
            }

        }else{
            $this->error(L('_ERROR_ACTION_'));
        }
    }


}