<?php
/**
*	artcategory_model.php
*	
*	@version 2015-5-4
*	@author wenxue1
*/
class ArtCategory extends DbMysql
{
	public $db;
	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->db = parent::_connect("default");
		$this->table = "art_category";
	}
	/**
	 * 总数
	 * @return unknown
	 */
	public function get_count()
	{
		$sql = "select count(1) as num from ".$this->table;
		$query =$this->db->query($sql);
		$result =  $query->fetch_assoc();
		$count = isset($result["num"])? $result["num"]:0;
		return $count;
	}
	/**
	 * 获取单条信息
	 * @param string $category_id
	 * @return array
	 */
	public function get_one_by_category_id($category_id)
	{
		
		$sql = "select * from ".$this->table ."  where category_id=".(int)$category_id ;
		
		$query =$this->db->query($sql);
		
		$result =$query->fetch_assoc();
		return $result;
	}
	/**
	 * 根据条件获取内容
	 *
	 * @param unknown $search_where
	 * @return multitype: unknown
	 */
	public function search_result_from_where ( $search_where=array() )
	{
		$where = " where 1 ";
	
		foreach ( $search_where as $k => $v )
		{
			if ($v === false || "" === $v)
			{
				continue;
			}
			$where [$k] = $v;
			if ($k=="category_name") {
				$where.=" and category_name like '%".$v."%' ";
			}else 
			{
				$where.=" and ".$k ."='".$v."' ";
			}
		}
		
		$sql = "select * from ".$this->table . $where ;
		$query =$this->db->query($sql);
		$result =array();
		while ($row = $query->fetch_assoc())
		{
			$result[]=$row;
		}
		return $result;
	}
}