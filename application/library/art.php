<?php
/**
*	art.php
*	系统管理
*	@version 2015-1-7
*	@author wenxue1
*/
class art extends  CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library("viewpublic");
		$this->load->library("artcenter");
		$this->load->model("artlist_model","artlist");
		$this->load->model("artcategory_model","artcategory");

	}
	public function index()
	{
		$_html ="";
		$_html .=$this->viewpublic->getHeader();
		$_html .=$this->viewpublic->getMenu(array("active"=>"art"));
		
		$category_id = (int)$this->input->get("category_id",true);
		$category_id = ($category_id <1)?1:$category_id ;
		
		$offset=0;
		$limit =10;
		$cat_res = $this->artcategory-> get_one_by_category_id($category_id );
		$data["catename"] =isset($cat_res["category_name"])?$cat_res["category_name"]:"";
		$res_list = $this->artlist-> search_result_from_where(array("category_id"=>$category_id) ,$offset,$limit);
		
		$total = $this->artlist-> get_count( $category_id);
		$data["total"]= $total;
		$this->artcenter->set_cache_dir(ART_PATH);
		foreach($res_list as $key=>&$val)
		{
			$content = $this->artcenter->fyget($val["text_url"]);
			$val["content"]=strip_tags($content) ;		
			$val["detail_url"]= site_url(array("c"=>"art",
								"a"=>"detail",
								"art_id"=>$val["art_id"]
								) );
		}
		 $data["ajax_url"]=site_url(array("c"=>"art","a"=>"ajax_list","offset"=>($offset+1)*$limit,"limit"=>$limit,"category_id"=>$category_id)); 
		$data["tplVars"] =$res_list;
		$_html .=$this->load->view('article/index',$data,true);
	//	$_html .=$this->load->view('main/main',"",true);
		$_html .=$this->viewpublic->getFooter();
		
		echo $_html ;
	}
	public function ajax_list()
	{
		$offset = (int)$this->input->get("offset");
		$limit = (int)$this->input->get("limit");
		$category_id = (int)$this->input->get("category_id",true);
                $category_id = ($category_id <1)?1:$category_id ;
		$res_list = $this->artlist-> search_result_from_where(array("category_id"=>$category_id) ,$offset,$limit);
		 $this->artcenter->set_cache_dir(ART_PATH);
                foreach($res_list as $key=>&$val)
                {
                        $content = $this->artcenter->fyget($val["text_url"]);
                        $val["content"]=strip_tags($content) ;
                        $val["detail_url"]= site_url(array("c"=>"art",
                                                                "a"=>"detail",
                                                                "art_id"=>$val["art_id"]
                                                                ) );
                }
                $data["tplVars"] =$res_list;
		$_html ="";
		if(!empty($res_list))
		{
			$_html .=$this->load->view('article/ajax_list',$data,true);
		}
		$return  =array("code"=>0,"content"=>$_html);
		$return["ajax_url"]=site_url(array("c"=>"art","a"=>"ajax_list","offset"=>($offset+1)*$limit,"limit"=>$limit,"category_id"=>$category_id));
		echo json_encode($return);
		
	}
	/**
	* 详细信息
	*/
	public function detail()
	{
		$_html ="";
		$_html .=$this->viewpublic->getHeader();
		$_html .=$this->viewpublic->getMenu(array("active"=>"art"));
		
		$art_id = (int)$this->input->get("art_id",true);
		$art_id = ($art_id <1)?1:$art_id ;
		$res_list = $this->artlist-> get_one_by_art_id($art_id);
		$this->artcenter->set_cache_dir(ART_PATH);
		if(!empty($res_list)&&!empty($res_list["text_url"]))
		{
			$content = $this->artcenter->fyget($res_list["text_url"]);
			$res_list["content"]= $content ;		
			
		}elseif(!empty($res_list))
		{
			$res_list["content"]="";
		}
		
		$data["tplVars"] =$res_list;
		$_html .=$this->load->view('article/detail',$data,true);
		$share=array();
		$url1 = site_url(array("c"=>"art",
                                                                "a"=>"detail",
                                                                "art_id"=>$art_id
                                                                ) );
		$share["url"][]=array("title"=>"QQ靠","url"=>"http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=".urlencode($url1)."&title=".urlencode($res_list["art_name"]));
		$_html .=$this->load->view('article/share',$share,true);
		$_html .=$this->viewpublic->getFooter();
		
		echo $_html ;
	}
	
	
	
	
}
