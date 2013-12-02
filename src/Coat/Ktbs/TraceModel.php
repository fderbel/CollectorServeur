<?php
namespace Coat\Ktbs;
use Coat\Ktbs\Utils\RestfulHelper;
use Coat\Ktbs\Cache\Cache;

class TraceModel {
	public $uri = null;
	public $name = null;
	public $label = null;
	public $base_uri = null;
	
	function __construct($base_uri, $name){
		$this->name = $name;
		$this->base_uri = $base_uri;
		$this->uri = $this->base_uri.$name;
	}
	
	function exist(){
		
		$cache = new KtbsResourceCache($this->uri);
		list($isOK, $retval) = $cache->read();
		
		if($isOK)
			return $retval;
		
		$this->exist = RestfulHelper::get($this->uri);
		
		if($this->exist){
			$cache = new KtbsResourceCache($this->uri);
			$cache->write($this->exist);
		}
		
		return $this->exist;
	}

	function loadScriptAndDump(){
		$dir = dirname(__FILE__);
		$script = file_get_contents($dir."/scripts/mod_model1.ttl");
		
		RestfulHelper::post($this->base_uri, $script);		
		
		$script1 = file_get_contents($dir."/scripts/gra_model1.ttl");
		
		RestfulHelper::getEtagAndPut($this->uri, $script1);
	}
		
	function dump(){
		
	}
}
?>