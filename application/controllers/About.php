<?php
class AboutController extends BasicController 
{
	public function init()
	{
		parent::init();
	}
	
   public function indexAction() 
   {
   		$_html="";
   		$this->initView();
   		$_html .=$this->html_header(array("page"=>"about"));
   		
        $_html.= $this->render("index");
        $_html .=$this->html_footer();
        echo $_html;
        
   }
   
   
}
?>