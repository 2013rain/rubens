<?php
/**
*	DbMysql.php
*	
*	@version 2016-1-11
*	@author wenxue1
*/
class DbMysql
{
	public $con_list =array();
	public $config =array();
	public function __construct()
	{
		self::initConfig();	
	}
	private  function initConfig()
	{
		if (empty($this->config)) 
		{
			$this->config = Yaf_Registry::get("dbconfig");
		}
	}
	/**
	 * 连接数据库
	 * @param string $db
	 * @return boolean|unknown
	 */
	public function _connect($dbkey="")
	{
		if (! isset($this->config[$dbkey])) 
		{
			echo "Failed to connect to MySQL, no this database config" ;
			return false;
		}
		if (isset($this->con_list[$dbkey])) 
		{
			return $this->con_list[$dbkey];
		}else 
		{
			$localhost = $this->config[$dbkey]["hostname"];
			$username = $this->config[$dbkey]["username"];
			$password = $this->config[$dbkey]["password"];
			$database = $this->config[$dbkey]["database"];
			$port =  $this->config[$dbkey]["port"];
			$char_set =  $this->config[$dbkey]["char_set"];
			
			$mysqli = new mysqli($localhost ,$username , $password , $database ,$port );
			if ($mysqli->connect_errno) 
			{
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				echo $mysqli->host_info . "\n";
				return false;
			}else 
			{
				$mysqli->query("set name $char_set");
				$this->con_list[$dbkey]=$mysqli;
				return $mysqli;
			}
		}
	}
	
}