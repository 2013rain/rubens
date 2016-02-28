<?php
class ArtController extends BasicController 
{
	public function init()
	{
		parent::init();
		Yaf_Loader::import('Artcenter.php');
		$this->artcenter = new Artcenter();
		$this->artcenter->set_cache_dir(ART_PATH);
		
		Yaf_Loader::import(APP_PATH .'/application/models/Artlist.php');
		$this->artlist = new Artlist();
		
		Yaf_Loader::import(APP_PATH .'/application/models/ArtCategory.php');
		$this->artcategory = new ArtCategory();
	}
	
   public function indexAction() 
   {
   		$_html="";
   		$this->initView();
   		
   		$category_id = (int)$this->get("category_id");
   		
   		$keyword = trim($this->get("keyword"));
   		
   		$page = (int)$this->get("page");
   		$pagesize = (int)$this->get("pagesize");
   		$pagesize =10;
   		$page =$page<1?1:$page;
   		$offset = ($page-1)*$pagesize;
   		$_html .=$this->html_header(array("page"=>"art"));
   		
   		$category_list = $this->artcategory->search_result_from_where();
   		
   		$art_id_list =array();
   		$total = 0;
   		if (empty($keyword)) 
   		{
   			$category_id=empty($category_id)? $category_list[0]["category_id"]: $category_id;
   			$art_id_list = $this->artlist->get_id_from_category($category_id ,$offset ,$pagesize);
   			$total = $this->artlist->get_count_from_category($category_id);
   		}else 
   		{
   			//搜索文章
   			$art_id_list = $this->artlist->get_id_from_name($keyword,$offset ,$pagesize);
   			$total = $this->artlist->get_count_from_name($keyword);
   		}
   		
   		$art_list = array();
   		
   		if (!empty($art_id_list)) 
   		{
   			$art_list = $this->artlist->search_result_from_where(array("art_id"=> $art_id_list));
   		}
   		$category_name_list =array();
   		foreach ($category_list as $c1=>&$cv1)
   		{
   			$cv1["url"]=$this->url("/art",array("category_id"=>$cv1["category_id"]));
   			$cv1["class"]= $cv1["category_id"]==$category_id?"active":"";
   			$category_name_list[$cv1["category_id"]] = $cv1["category_name"];
   		}
   		 
		$replace_before=array(
				$keyword,
				strtoupper($keyword),
				strtolower($keyword)
		);  
		$replace_after=array(
				"<font color='red'>" . $keyword ."</font>",
				"<font color='red'>" .strtoupper($keyword)."</font>",
				"<font color='red'>" .strtolower($keyword)."</font>"
		);
   		foreach($art_list as $key=>&$val)
   		{
   			$content = $this->artcenter->fyget($val["text_url"]);
   			
   			$val["art_name"] = str_replace($replace_before, $replace_after , $val["art_name"]);
   			$val["content"]= str_replace(array(" ","\t"), array("","") ,strip_tags($content) ) ;
   			$val["content"] = $this->subString($val["content"],200);
   			$val["detail_url"]=$this->url("/art/detail",array("art_id"=>$val["art_id"]));
   			$val["category_url"]=$this->url("/art",array("category_id"=>$val["category_id"]));
   			$val["category_name"]=$category_name_list[$val["category_id"]];
   		}
   		
   		$pagelist = array();
   		$totalpage = ceil($total/$pagesize);
   		if ($pagesize<$total) 
   		{
   			$i =0;
   			$m =$totalpage;
   			$i = $page<3||$m<6    ?$i:  ($m>5 &&$page>2&&$page< ($m-2) ?$page-2 : $m>5 &&$page> ($m-2)?$m-5 :$i ); 
   				for($i;$i<$m;$i++)
   				{
   					$the_page = $i+1;
   					$url = $page==$the_page?"javascript:void(0)":$this->url("/art",array("page"=>$the_page,"category_id"=>$category_id,"keyword"=>urlencode($keyword) ) );
   					
   					$class = $page==$the_page?"active":"";
   					
   					$pagelist[]=array("index"=>$the_page,
   					"url"=>$url,
   					"class"=>$class
   					);
   				}
   		}
   		$this->_view->assign("totalpage", $totalpage);
   		$this->_view->assign("pagelist", $pagelist);
   		$this->_view->assign("category_list", $category_list);
   		$this->_view->assign("category_id", $category_id);
        $this->_view->assign("art_list", $art_list);
        $_html.= $this->render("index");
        $_html .=$this->html_footer();
        echo $_html;
        
   }
   
   public function detailAction()
   {
	   	$_html="";
	   	$this->initView();
	   	 
	   	$art_id = (int)$this->get("art_id");
	   	if ($art_id<1) 
	   	{
	   		$this->redirect("/art/");
			return ;
	   	}
	   	$art_res = $this->artlist->get_one_by_art_id($art_id);
	   	if (empty($art_res)) 
	   	{
	   		$this->redirect("/art/");
			return ;
	   	}
	   	$art_cate = $this->artcategory->get_one_by_category_id($art_res["category_id"]);
	   	
	   	$content = $this->artcenter->fyget($art_res["text_url"]);
	   	$art_res["content"] = $content;
	   	$art_res["category_name"]=$art_cate["category_name"];
	   	
	   	$art_res["category_url"]=$this->url("/art",array("category_id"=>$art_res["category_id"]));
	   	$title = 	$art_res["art_name"];
	   	
	   	$_html .=$this->html_header(array("page"=>"art","title"=>$title));
	   	
	   	$this->_view->assign("art_res", $art_res);
	   	$_html.= $this->render("detail");
		
		$duoshuo=array(
		"id"=>$art_id,
		"title"=>$art_res["art_name"],
		"url"=>urlencode("http://www.wangfuyu.com.cn".  $this->url("/art/detail",array("art_id"=>$art_id))) 
		);
		$_html .=$this->html_duoshuo($duoshuo);
	   	$_html .=$this->html_footer();
	   	echo $_html;
   }
   
   
}
?>
