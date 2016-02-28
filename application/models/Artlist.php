<?php
/**
*	Artlist.php
*	
*	@version 2015-5-4
*	@author wenxue1
*/
class Artlist extends DbMysql
{
	public $db;
	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->db = parent::_connect("default");
		$this->table = "art_list";
		$this->art_category ="art_category";
	}
	/**
	 * 最新的6篇
	 * @return unknown
	 */
	public function get_top_list($num=10)
	{
		
		$sql = "select ".$this->table.".*,".$this->art_category.".category_name from ".$this->table  ;
		$sql .=" left join ".$this->art_category ."  on ".$this->art_category  .".category_id = ".$this->table.".category_id";
		$sql .="  order by " . $this->table.".modify_time desc";
		$sql .="  limit 0,$num";
		$query =$this->db->query($sql);
		
		$result =array();
		while ($row = $query->fetch_assoc())
		{
			$result[]=$row;
		}
		return $result;
		
	}
	/**
	 * 总数
	 * @return unknown
	 */
	public function get_count_from_category($category_id=0)
	{
		$count =0;
		if($category_id>0)
		{
			
			$sql = "select count(1) as num from ".$this->table ." where category_id=".(int)$category_id;
			$query =$this->db->query($sql);
			$result =  $query->fetch_assoc();
			$count = isset($result["num"])? $result["num"]:0;
			return $count;
		}
		return $count;
	}
	/**
	 * 总数
	 * @return unknown
	 */
	public function get_id_from_category($category_id=0,$offset=0,$limit=100)
	{
		$where = "  where 1 and category_id=".(int)$category_id;
		
		$sql = "select  art_id from  ".$this->table . $where;
		$sql .="  order by modify_time desc";
		$sql .="  limit $offset,$limit";
		$query =$this->db->query($sql);
		$result =array();
		while ($row = $query->fetch_assoc())
		{
			$result[]=$row["art_id"];
		}
		return $result;
	}
	/**
	 * 获取单条信息
	 * @param string $category_id
	 * @return array
	 */
	public function get_one_by_art_id($art_id)
	{
		
		$sql = "select * from ".$this->table ." where art_id=".(int)$art_id;
		$query =$this->db->query($sql);
		$result =  $query->fetch_assoc();
		return $result;
		
	}
	/**
	 * 总数
	 * @return unknown
	 */
	public function get_count_from_name($art_name="")
	{
		$art_name = addslashes($art_name);
		$where = "  where 1 and art_name like '%".$art_name."%' ";
		
		$sql = "select  count(1) as num from  ".$this->table . $where;
		$query =$this->db->query($sql);
		$row = $query->fetch_assoc();
		$count = isset($row["num"])?$row["num"]:0;
		return $count;
	}
	/**
	 * 根据条件获取id
	 *
	 * @param unknown $search_where
	 * @return multitype: unknown
	 */
	public function get_id_from_name ( $art_name="" ,$offset=0 ,$limit=100)
	{
		$art_name = addslashes($art_name);
		$where = "  where 1 and art_name like '%".$art_name."%' ";
		
	
		$sql = "select  art_id from  ".$this->table . $where;
		$sql .="  order by modify_time desc";
		$sql .="  limit $offset,$limit";
		$query =$this->db->query($sql);
		$result =array();
		while ($row = $query->fetch_assoc())
		{
			$result[]=$row["art_id"];
		}
		return $result;
	}
	
	/**
	 * 根据条件获取内容
	 *
	 * @param unknown $search_where
	 * @return multitype: unknown
	 */
	public function search_result_from_where ( $search_where=array() ,$offset=0 ,$limit=100)
	{
		
		$where = "  where 1 ";
		
		foreach ( $search_where as $k => $v )
		{
			if(is_array($v) && !empty($v))
			{
				$v = implode(",", $v);
				$where.=" and ". $k ." in  (" . $v .")"; 
				continue;
			}
			if ($v === false || "" === $v)
			{
				continue;
			}
			$where [$k] = $v;
			if ($k=="art_name") {
				$where.=" and .art_name like '%".$v."%' ";
			}else
			{
				$where.=" and ".$k ."='".$v."' ";
			}
		}
		
		$sql = "select * from  ".$this->table . $where;
		$sql .="  order by modify_time desc";
		$sql .="  limit $offset,$limit";
		
		$query =$this->db->query($sql);
		$result =array();
		while ($row = $query->fetch_assoc())
		{
			$result[]=$row;
		}
		
		return $result;
	}
}
