<?php

class Bootstrap extends Yaf_Bootstrap_Abstract
{

    private $_config;

    public function _initConfig()
    {
        $this->_config = Yaf_Application::app()->getConfig();
        Yaf_loader::import(APP_PATH . '/conf/database.php');
        Yaf_Registry::set ( "dbconfig" , $db);
    }

    public function _initErrors()
    {
        if ($this->_config->application->showErrors) 
        {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }else 
        {
        	ini_set('display_errors', '0');
        }
    }


    public function _initCore()
    {
        Yaf_Loader::import('BasicController.php');
        Yaf_Loader::import('BasicModel.php');
        Yaf_Loader::import('DbMysql.php');
    }

    public function _initLogs()
    {
    	//日志文本
		$admin_log_file = WFY_DATA_PATH ."logs/rain_".date("Ymd").".log";
		$path ="";
	    if(strstr($_SERVER['REQUEST_URI'], '?')){
			$arr = explode('?', $_SERVER['REQUEST_URI']);
			$path = $arr[0];
			unset($arr);
		}else{
			$path = $_SERVER['REQUEST_URI'];
		}
		
		//不记录日志的链接
		$_log_str = array();
		
		$uid = isset($_COOKIE["uid"])?$_COOKIE["uid"]:$this->get_gen_id($_SERVER["REMOTE_ADDR"]);
		$_log_str['uid']=  strip_tags($uid);//有效访客
		$_log_str['day']=  strip_tags(date("Ymd"));
		$_log_str['time']=  strip_tags(date("H:i:s"));
		$_log_str['refer']=  strip_tags(@$_SERVER['HTTP_REFERER']);
		$_log_str['agent']=  urlencode(strip_tags( $_SERVER['HTTP_USER_AGENT'] ));
		$_log_str['IP']=  strip_tags($_SERVER["REMOTE_ADDR"]);
		$_log_str['REQUEST_URI']=  strip_tags ($_SERVER["REQUEST_URI"] );
		$_log_str['SERVER_NAME']=  strip_tags($_SERVER["SERVER_NAME"]);
		$_log_str['method']=  strip_tags($_SERVER['REQUEST_METHOD']);
		 $_log_str['path']=urlencode($path);
		$_log_str['finishLog']="ok";
		$log_str ="";
		foreach($_log_str as $k=>$v)
		{
			$log_str.=$k."[".$v."]\t";
		}
		$log_str .=PHP_EOL;
		file_put_contents($admin_log_file,$log_str,FILE_APPEND);
    }
    public function _initView(Yaf_Dispatcher $dispatcher)
    {
        //关闭自动渲染模板功能
//         Yaf_Dispatcher::getInstance()->autoRender(false);
    }
	
    public function  get_gen_id($ip)
    {
	//新的方法
		$key_array =array();
		$key_array[0]= ip2long($ip);
		$key_array[1]= time();
		$key_array[2]=rand(0,  65526);
		$key_array[3]= array_sum($key_array);
		$uid = strtoupper( md5(implode("", $key_array)) );
		setcookie('uid',$uid);
		return  $uid;
    }

}
