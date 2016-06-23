<?php
/**
 * Created by PhpStorm.
 * User: Mike Hu
 * Date: 2016/4/15
 * Time: 11:18
 */
namespace Home\Controller;

use Think\Controller;

set_time_limit(0);
class FinanceController extends BaseController {
    public function index() {
        $this->display();
    }

    public function upload() {
        $this->display();
    }

    //报销数据
    public function expenseList(){
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = '';
        }
        $expense = D('expense');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $appr_num = trim($_REQUEST['appr_num']);
        if ($appr_num != null) {
            $where .= " AND appr_num LIKE '%{$appr_num}%'";
        }
        $where .= " AND `start_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $expense->where($where)->count();
        $totalAmounts = $expense->where($where)->field('SUM(`detail_amount`) as totalAmount')->find();
        $totalAmount = isset($totalAmounts['totalamount']) ? $totalAmounts['totalamount'] : 0;
        $totalAmount = number_format ( $totalAmount ,  2 ,  '.' ,  ',' );
        $list = $expense->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('appr_num', $appr_num);
        $this->assign('year_month', $year_month);
        $this->assign('totalAmount', $totalAmount);
        $this->display();
    }

    //预支数据
    public function advanceList(){
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = '';
        }
        $advance = D('advance');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $appr_num = trim($_REQUEST['appr_num']);
        if ($appr_num != null) {
            $where .= " AND appr_num LIKE '%{$appr_num}%'";
        }

        $where .= " AND `start_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $advance->where($where)->count();
        $totalAmounts = $advance->where($where)->field('SUM(`detail_amount`) as totalAmount')->find();
        $totalAmount = isset($totalAmounts['totalamount']) ? $totalAmounts['totalamount'] : 0;
        $totalAmount = number_format ( $totalAmount ,  2 ,  '.' ,  ',' );
        $list = $advance->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('appr_num', $appr_num);
        $this->assign('year_month', $year_month);
        $this->assign('totalAmount',$totalAmount);
        $this->display();

    }
    //固定资产数据
    public function fixedAssetList(){
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = '';
        }
        $fixedAsset = D('fixed_asset');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $appr_num = trim($_REQUEST['appr_num']);
        if ($appr_num != null) {
            $where .= " AND appr_num LIKE '%{$appr_num}%'";
        }

        $where .= " AND `start_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $fixedAsset->where($where)->count();
        $totalAmounts = $fixedAsset->where($where)->field('SUM(`detail_price`) as totalAmount')->find();
        //debug($totalAmounts);
        $totalAmount = isset($totalAmounts['totalamount']) ? $totalAmounts['totalamount'] : 0;
        $totalAmount = number_format ( $totalAmount ,  2 ,  '.' ,  ',' );
        $list = $fixedAsset->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('appr_num', $appr_num);
        $this->assign('year_month', $year_month);
        $this->assign('totalAmount',$totalAmount);
        $this->display();

    }
    //办公用品数据
    public function officeSupplies(){
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = '';
        }
        $officeSupplies = D('office_supplies');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $appr_num = trim($_REQUEST['appr_num']);
        if ($appr_num != null) {
            $where .= " AND appr_num LIKE '%{$appr_num}%'";
        }

        $where .= " AND `start_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $officeSupplies->where($where)->count();
        $totalAmounts = $officeSupplies->where($where)->field('SUM(`detail_price`) as totalAmount')->find();
        $totalAmount = isset($totalAmounts['totalamount']) ? $totalAmounts['totalamount'] : 0;
        $totalAmount = number_format ( $totalAmount ,  2 ,  '.' ,  ',' );
        $list = $officeSupplies->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('appr_num', $appr_num);
        $this->assign('year_month', $year_month);
        $this->assign('totalAmount',$totalAmount);
        $this->display();
    }

    //资产验收数据
    public function assetAcceptance(){
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = '';
        }
        $assetAcceptance = D('asset_acceptance');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $appr_num = trim($_REQUEST['appr_num']);
        if ($appr_num != null) {
            $where .= " AND appr_num LIKE '%{$appr_num}%'";
        }

        $where .= " AND `start_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $assetAcceptance->where($where)->count();

        $list = $assetAcceptance->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('appr_num', $appr_num);
        $this->assign('year_month', $year_month);
        $this->display();
    }

    //导出资源验收数据
    public function assetAcceptanceExport(){
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = '';
        }
        $assetAcceptance = D('asset_acceptance');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $appr_num = trim($_REQUEST['appr_num']);
        if ($appr_num != null) {
            $where .= " AND appr_num LIKE '%{$appr_num}%'";
        }

        $where .= " AND `start_time` LIKE '{$year_month}%' ";

        $data = $assetAcceptance->where($where)->field('people_name,department,appr_num,title,status,result,start_time,end_time,purchase_num,purchase_depart,acceptance_result,date,use_depart,use_people')->select();
        $filename="assetAcceptance";
        $headArr = array('姓名','部门','审批编号','标题','审批状态','审批结果','审批发起时间','审批完成时间','采购编号','采购部门','验收情况','日期','领用部门','领用人');
        exportExcel($filename, $headArr, $data);
    }

    //合同数据
    public function contract(){
        if (isset($_REQUEST['year_month']) && $_REQUEST['year_month'] != null) {
            $year_month = trim($_REQUEST['year_month']);
        } else {
            $year_month = '';
        }
        $contract = D('contract');
        $where = '1=1';
//        $where .= " AND status = '完成' AND result = '同意'" ;
        $staff_name = trim($_REQUEST['staff_name']);
        if ($staff_name != null) {
            $where .= " AND people_name LIKE '%{$staff_name}%' ";
        }
        $appr_num = trim($_REQUEST['appr_num']);
        if ($appr_num != null) {
            $where .= " AND appr_num LIKE '%{$appr_num}%'";
        }

        $where .= " AND `start_time` LIKE '{$year_month}%' ";

        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $count = $contract->where($where)->count();
        $totalAmounts = $contract->where($where)->field('SUM(`amount`) as totalAmount')->find();
        //debug($totalAmounts);
        $totalAmount = isset($totalAmounts['totalamount']) ? $totalAmounts['totalamount'] : 0;
        $totalAmount = number_format ( $totalAmount ,  2 ,  '.' ,  ',' );
        $list = $contract->where($where)->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->assign('staff_name', $staff_name);
        $this->assign('appr_num', $appr_num);
        $this->assign('year_month', $year_month);
        $this->assign('totalAmount', $totalAmount);
        $this->display();
    }

}