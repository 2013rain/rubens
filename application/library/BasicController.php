<?php

class BasicController extends Yaf_Controller_Abstract
{
    public function init()
    {
        Yaf_Dispatcher::getInstance()->disableView();
       
    }
    
    public function get($key, $filter = true)
    {
    	return $this->getRequest()->getQuery($key);
    	
    }

    public function post($key, $filter = true)
    {
    	return $this->getRequest()->getPost($key);
    }

    public function getParam($key, $filter = true)
    {
    	return $this->getRequest()->getParam($key);
           
    }
    
    public function url($uri,$param=array())
    {
    	$url ="";
    	if (strstr($uri, "=")!==false) 
    	{
    		$url =trim($uri,"&") ."&" .http_build_query($param);
    	}else 
    	{
    		$url =trim($uri,"?") ."?" .http_build_query($param);
    	}
    	return $url;
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
    
    /**
     * 导航列表
     * @param string $page
     * @return string
     */
    private function data_navlist($page="index")
    {
    	$nav_list =array();
    	$nav_list["index"]=array(
	    			"name"=>"主页",
	    			"url"=>$this->url("/"),
	    			"class"=>""
    			);
    	$nav_list["art"]=array(
    			"name"=>"个人日志",
    			"url"=>$this->url("/art"),
    			"class"=>""
    	);
    	$nav_list["about"]=array(
    			"name"=>"关于网站",
    			"url"=>$this->url("/about"),
    			"class"=>""
    	);
    	$nav_list["game"]=array(
    			"name"=>"游戏频道",
    			"url"=>$this->url("/game"),
    			"class"=>""
    	);
    	$nav_list["music"]=array(
    			"name"=>"音乐频道",
    			"url"=>$this->url("/music"),
    			"class"=>""
    	);
    	$nav_list["book"]=array(
    			"name"=>"阅读频道",
    			"url"=>$this->url("/book"),
    			"class"=>""
    	);
    	if (key_exists($page, $nav_list)) 
    	{
    		$nav_list[$page]["class"]="active";
    	}
    	
    	return $nav_list;
    }
    public function html_header($param =array())
    {
    	$title = isset($param["title"]) &&!empty($param["title"])?$param["title"]:"";
    	$page = isset($param["page"]) &&!empty($param["page"])?$param["page"]:"index";
    	$this->initView();
    	$nav_list = $this->data_navlist($page);
    	
    	$title1 = "Rubeus ".$nav_list[$page]["name"] . $title;
	$title= empty($title)?$title1:$title;	

    	$this->_view->assign("title", $title);
    	
    	$this->_view->assign("nav_list", $nav_list);
    	return $this->render("../header");
    }
    public function html_footer($param =array())
    {
    	$this->initView();
    	return $this->render("../footer");
    }

    public function html_duoshuo($param =array())
    {
	$tplVars =array();
	$tplVars["id"]= isset($param["id"]) ?$param["id"] :"";
        $tplVars["title"]= isset($param["title"]) ?$param["title"] :"";
	$tplVars["url"]= isset($param["url"]) ?$param["url"] :"";

       if(empty($tplVars["id"]) || empty($tplVars["title"]) || empty($tplVars["url"])  )
	{return "";}
	$this->initView();
        $this->_view->assign("tplVars", $tplVars);
        return $this->render("../duoshuo");
    }
}
