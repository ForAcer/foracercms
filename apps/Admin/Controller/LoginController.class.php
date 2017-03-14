<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\AdminModel;

defined('SITE_PATH') or die('Access Denied');

class LoginController extends Controller {

	public function index(){
		if(!empty($_SESSION['adminInfo']))
		{
			redirect(U('Admin/Index/webinfo'));
		}

		$this->display();
	}

	public function createVerify()
	{
		$Verify = new \Think\Verify();
		$Verify->fontSize = 20;
		$Verify->length   = 4;
		$Verify->useNoise = false;
		$Verify->reset = false;
		$Verify->imageW = 130;
		$Verify->imageH = 34;
		$Verify->entry();
	}

	public function loginCheck()
	{
		$return = array('code'=>0, 'msg'=>'', 'data'=>'');

		$passCode = C("passCode");
		$username = text($_POST['user']);
		$password = text($_POST['pass']);
		$verifyCode = text($_POST['code']);
		$truePassword = md5($password.$passCode);

		$where = array('username'=> $username, 'password'=>$truePassword, 'is_del'=> 0);
		if(check_verify($verifyCode))
		{
			$countLength = AdminModel::instance()->checkAdmin($where);
			if($countLength > 0)
			{
				$adminInfo = AdminModel::instance()->getAdminInfo($where);
				session(array('name'=>$adminInfo['username'], 'expire'=>1800));
				session('adminInfo', $adminInfo);
				$return = array('code'=>200, 'msg'=>'登录成功', 'data'=>'');
				exit(json_encode($return));
			}
			else
			{
				session_destroy();
				$return['msg'] = '用户名或者密码错误！';
				exit(json_encode($return));
			}
		}
		else
		{
			session_destroy();
			$return['msg'] = '验证码错误！';
			exit(json_encode($return));
		}
	}

	public function cleanCacheFun()
	{
		// 清文件缓存
		$dirs = $_SERVER['DOCUMENT_ROOT'] . '/runtime/Cache/';
		$this->new_rmdir($dirs, false);
		$return = array('code'=>200, 'msg'=>'清除缓存成功', 'data'=>'');
		exit(json_encode($return));
	}

	public function loginOutFun()
	{
		session_destroy();
		$return = array('code'=>200, 'msg'=>'退出系统成功', 'data'=>'');
		exit(json_encode($return));
	}

	/**
	 * 清空/删除 文件夹
	 * @param string $dirname 文件夹路径
	 * @param bool $self 是否删除当前文件夹
	 * @return bool
	 */
	private function new_rmdir($dirname, $self = true)
	{
		if (!file_exists($dirname))
		{
			return false;
		}
		if (is_file($dirname) || is_link($dirname))
		{
			return unlink($dirname);
		}
		$dir = dir($dirname);
		if ($dir)
		{
			while (false !== $entry = $dir->read())
			{
				if ($entry == '.' || $entry == '..')
				{
					continue;
				}
				$this->new_rmdir($dirname . '/' . $entry);
			}
		}
		$dir->close();
		$self && rmdir($dirname);
	}
}