<?php
class MusicController extends BasicController 
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
	
	public function indexAction() 
   {
   		$_html="";
   		$this->initView();
   		$_html .=$this->html_header(array("page"=>"music"));
   		
        $_html.= $this->render("index");
        $_html .=$this->html_footer();
        echo $_html;
        
   }
   
   
}
?>