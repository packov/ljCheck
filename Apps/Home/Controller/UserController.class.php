<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends BaseController {
    //用户列表
    public function userList() {
        $user = D('user');
        $count = $user->count();
        $page = $_REQUEST['page'] ? trim($_REQUEST['page']) : 1;
        $pageSize = C('pageSize');
        $list = $user->limit(($page - 1) * $pageSize, $pageSize)->select();
        $pageNum = ceil($count / $pageSize);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('pageNum', $pageNum);
        $this->display();
    }

    //添加用户
    public function addUser() {
        if($_SESSION['username']!='admin'){
            $this->error('非admin用户,没有添加用户权限!',U('User/userList'),1);
        }
        $this->display();
    }

    public function doAddUser() {
        $return = array();
        $return['error'] = 1;
        $data = array();
        $data['name'] = I('post.name');
        $data['password'] = md5(I('post.password'));
        $confirm_password = md5(I('confirm_password'));
        $data['reg_time'] = date("Y-m-d H:i:s", time());
        if($_SESSION['username']!='admin'){
            $return['msg'] = '非admin用户,没有添加用户权限!';
            $this->ajaxReturn($return);
        }
        $user = D('user');
        if ($data['name'] == null) {
            $return['msg'] = '请填写用户名!';
            $this->ajaxReturn($return);
        } elseif($user->where("name='{$data['name']}'")->select()) {
            $return['msg'] = $data['name'].':用户名已存在!';
            $this->ajaxReturn($return);
        }

        if ($data['password'] == null) {
            $return['msg'] = '请填写密码!';
            $this->ajaxReturn($return);
        }

        if ($data['password'] != $confirm_password) {
            $return['msg'] = '密码与确认密码不匹配!';
            $this->ajaxReturn($return);
        }

        if ($user->add($data)) {
            $return['error'] = 0;
            $return['msg'] = '添加用户成功!';
        }
        $this->ajaxReturn($return);

    }

    public function updateUser() {
         $userId = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
         $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
         $this->assign('userId', $userId);
         $this->assign('name', $name);
         $this->display();
    }

    public function doUpdateUser(){
        //debug($_POST);
        $return = array();
        $return['error'] = 1;
        $return['msg'] = '修改用户失败!';
        $data = array();
        $id = I('post.id');
        $data['name'] = I('post.name');
        $oldPassword = md5(I('post.oldPassword'));
        $new_password = md5(I('post.new_password'));
        $confirm_password = md5(I('post.confirm_password'));
        $data['reg_time'] = date("Y-m-d H:i:s", time());

        $user = D('user');
        $where = " `name`='{$data['name']}' AND `password`='$oldPassword' ";
        $ret = $user->where($where)->select();
        if ($ret == null){
            $return['msg'] = '原密码不正确!';
            $this->ajaxReturn($return);
        }
        if ($new_password != $confirm_password) {
            $return['msg'] = '新密码与确认密码不匹配!';
            $this->ajaxReturn($return);
        }
        $data['password'] = $new_password;
        if ($data['password'] == null) {
            $return['msg'] = '请填写新密码!';
            $this->ajaxReturn($return);
        }
        if ($data['name'] == null) {
            $return['msg'] = '请填写用户名!';
            $this->ajaxReturn($return);
        }

        if ($user->where("name='{$data['name']}'")->save($data)) {
            $return['error'] = 0;
            $return['msg'] = '修改用户成功!';
        }
        $this->ajaxReturn($return);
    }

    public function deleteUser() {
        if($_SESSION['username']!='admin'){
            $this->error('非admin用户,没有删除用户权限!',U('User/userList'),1);
        }
        $id = intval($_REQUEST['id']);
        $user = D('user');
        if ($user->where("id={$id}")->delete()) {
            //$this->success("删除成功!", U('Staff/staffList'));
            redirect(U('User/userList'));
            return;
        } else {
            $this->error('操作失败!', U('User/userList'));
            return;
        }
    }

}