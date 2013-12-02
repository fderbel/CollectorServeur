<?php
namespace Coat\Ktbs\Utils;
class URIParser{
	
	static function getInstance(){
		return new URIParser();
	}
	
	function getName($uri){
		
		$uri = trim($uri,'/#');		
		$pos1 = strrpos($uri, '/');
		$pos2 = strrpos($uri, '#');
		$pos = max($pos1,$pos2);
		$name = substr($uri,$pos+1);
		return $name;
	}
	
	function getNames($uri_list){
		$ret = array();
		foreach($uri_list as $uri){
			$ret[] = $this->getName($uri);
		}
		return $ret;	
	}	
}
?>
