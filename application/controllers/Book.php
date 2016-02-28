<?php
class BookController extends BasicController 
{
	public function init()
	{
		parent::init();
	}
	
	public function indexAction() 
   {
   		$_html="";
   		$this->initView();
   		
   		$_html .=$this->html_header(array("page"=>"book"));
   		
        $_html.= $this->render("index");
        $_html .=$this->html_footer();
        echo $_html;
        
   }
   
   
}
?>