<?php
/**
*	artcenter.php
*	 文章操作
*	@version 2015-5-5
*	@author wenxue1
*/
class Artcenter
{
	/**
		 * cache source dir
		 * @var string
		 */
		protected $cache_dir;
		/**
		 * extension name
		 * @var string
		 */
		protected $_EXT =".fy";
		
		protected $version ="v1";
	
		public function __construct()
		{
			
		}
		 public function set_cache_dir($cache_dir)
		 {
		 	$this->cache_dir = $cache_dir;
		 }
		 public function get_cache_dir()
		 {
		 	return $this->cache_dir;
		 }
		 /**
		  * 自动生成文件名
		  * @param number $category_id
		  * @return string
		  */
		 public function get_file_name($category_id=0)
		 {
		 	if ($category_id==0) 
		 	{
		 		$category_id = date("Ym");
		 	}
		 	$key_array =array();
		 	$ip =$_SERVER["REMOTE_ADDR"];
		 	$key_array[0]= ip2long($ip);
		 	$key_array[1]= time();
		 	$key_array[2]=rand(0,  65526);
		 	$key_array[3]= array_sum($key_array);
		 	$uid = strtolower( md5(implode("", $key_array)) );
		 	
		 	return $category_id.DIRECTORY_SEPARATOR.$uid.$this->_EXT;
		 }
		
		 /**
		  * This method can add , modify , replace the content
		  * @param string $name
		  * @param string $value   the content
		  * @param string $life_time   life_time
		  */
		 public function fyset($filename,$value)
		 {
		 	$file = $this->cache_dir.$filename;
		 	$this->__mkdirs(dirname($file));
// 		 	var_dump($value);exit;
		 	return file_put_contents($file, $value);
		 }
		 /**
		  * This method can get the content
		  * @param string $name
		  */
		 public function fyget($filename)
		 {
		 	$file = $this->cache_dir.$filename;
		 	
		 	if( !is_readable($file) )return FALSE;
		 	$conent = file_get_contents($file);
		 	return $conent;
// 		 	var_dump($conent);
// 		 	var_dump(substr($conent, 14));exit;
		 	return  substr($conent, 14); //
		 	
		 }
		 /**
		  * This method can delete the content
		  * @param string $name
		  */
		 public function fydelete($filename)
		 {
		 		$file = $this->cache_dir.$filename;
		 	
			 	if( !is_readable($file) )return FALSE;
		 		return unlink($file);
		 		
		 }
		 /**
		  * The method can recursively create the directory
		  * @param string $dir
		  * @param string $mode
		  * @return boolean
		  */
		public function __mkdirs($dir, $mode = 0777)
		{
			if (!is_dir($dir)) {
				$this->__mkdirs(dirname($dir), $mode);
				return @mkdir($dir, $mode);
			}
			return true;
		}
		
		public  function subString($String,$Length) {
			if (mb_strwidth($String, 'UTF8') <= $Length ){
				return $String;
			}else{
				$I = 0;
				$len_word = 0;
				while ($len_word < $Length){
					$StringTMP = substr($String,$I,1);
					if ( ord($StringTMP) >=224 ){
						$StringTMP = substr($String,$I,3);
						$I = $I + 3;
						$len_word = $len_word + 2;
					}elseif( ord($StringTMP) >=192 ){
						$StringTMP = substr($String,$I,2);
						$I = $I + 2;
						$len_word = $len_word + 2;
					}else{
						$I = $I + 1;
						$len_word = $len_word + 1;
					}
					$StringLast[] = $StringTMP;
				}
				/* raywang edit it for dirk for (es/index.php)*/
				if (is_array($StringLast) && !empty($StringLast)){
					$StringLast = implode("",$StringLast);
					$StringLast .= "...";
				}
				return $StringLast;
			}
		}
}