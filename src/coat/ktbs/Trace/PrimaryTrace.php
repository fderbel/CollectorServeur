<?php
namespace Coat\Ktbs\Trace;
use Coat\Ktbs\Utils\RestfulHelper;
use Coat\ktbs\ClockEngine\CollectraClock;
use Coat\Ktbs\KtbsConfig;
use Coat\Ktbs\Trace\Trace;
use Coat\ktbs\utils\UserParser;

class PrimaryTrace extends Trace {
	
	function __construct($base_uri, $user, $model_uri){		
		
		$uri = new PrivateTraceUri($user, $base_uri);
		
		$this->base_uri = $uri->base_uri;
		$this->name = $uri->name;		
		$this->uri = $uri->uri;
		$this->unique_user_id = $uri->unique_user_id;
		$this->model_uri = $model_uri;
		$this->hasOrigin = CollectraClock::getNow();
	}
	
	function exist(){
		return parent::exist();
	}
	
	function dump(){
		$prefixes[] = "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .";
		$prefixes[] = "@prefix : <http://liris.cnrs.fr/silex/2009/ktbs#> .";				
		
		$statements[] = "<> :contains <".$this->name."> .";
		$statements[] = "<".$this->name."> a :StoredTrace .";
		$statements[] = "<".$this->name."> :hasModel <".$this->model_uri."> .";		
		$statements[] = "<".$this->name."> :hasOrigin ".'"'.$this->hasOrigin.'"'."^^xsd:dateTime .";
		
		
		$this->script = implode("\n", $prefixes)."\n"
					.implode("\n", $statements);
		
		$this->result = RestfulHelper::post($this->base_uri, $this->script);
	}
}

class PrimaryTraceUri{
	
	function __construct($user,$base_uri){
		global $wgUser;
		
		if($base_uri == null){
			$base_uri = KtbsConfig::getInstance()->getBaseURI();
		}
		
		if($user == null){			
			$user = $wgUser;						
		}
		
		$uparser = new UserParser($user);
		$this->unique_user_id = $uparser->getUniqueId();
		
		$this->base_uri = $base_uri;
		$this->name = "PrivateTrace_".$this->unique_user_id."/";		
		$this->uri = $this->base_uri.$this->name;
	}
}
