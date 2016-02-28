<?php
class IndexController extends BasicController 
{
	public function init()
	{
		parent::init();
		Yaf_Loader::import('Artcenter.php');
		$this->artcenter = new Artcenter();
		$this->artcenter->set_cache_dir(ART_PATH);
		
		Yaf_Loader::import(APP_PATH .'/application/models/Artlist.php');
		$this->artlist = new Artlist();
	}
   public function indexAction() {//默认Action
   		$_html="";
   		$this->initView();
   		
   		$_html .=$this->html_header(array("page"=>"index"));
   		
   		$res_list = $this->artlist->get_top_list(10);
   		
   		foreach($res_list as $key=>&$val)
   		{
   			$content = $this->artcenter->fyget($val["text_url"]);
   			$val["content"]= str_replace(array(" ","\t"), array("","") ,strip_tags($content) ) ;
   			$val["content"] = $this->subString($val["content"],200);
   			$val["detail_url"]=$this->url("/art/detail",array("art_id"=>$val["art_id"]));
   			$val["category_url"]=$this->url("/art",array("category_id"=>$val["category_id"]));
   		}
        $this->_view->assign("res_list", $res_list);
        $_html.= $this->render("index");
        $_html .=$this->html_footer();
        echo $_html;
        
   }
   
   
}
?>