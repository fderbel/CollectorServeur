<?php
require_once 'Claroline/CoreBundle/ktbs/utils/RestfulHelper.php';
require_once 'Claroline/CoreBundle/ktbs/utils/Log.php';
require_once 'Claroline/CoreBundle/ktbs/lib/SimpleRdfParser.php';
require_once 'Claroline/CoreBundle/ktbs/lib/rdfapi-php/api/util/RdfUtil.php';
require_once 'Claroline/CoreBundle/ktbs/KtbsResource.php';
require_once 'Claroline/CoreBundle/ktbs/utils/Minuterie.php';
require_once 'Claroline/CoreBundle/ktbs/cache/Cache.php';

class Trace{
	
	function __construct($trace_uri){
		$this->uri = $trace_uri;
	}
	
	function exist(){
		return RestfulHelper::get($this->uri);
	}
	
	function getObsels(){
		try{
			// get obsels from KTBS
			
			$minuterie = new RTraceMinuterie("Recuperer","Trace");
			$minuterie->start();
			
			$uri = $this->uri;
			$resource = new KtbsResource($uri);
			$subjects = $resource->getSubjects("@obsels",true,"json");
			
			$minuterie->load($resource,$subjects);
			$minuterie->finish();
			
			$this->obsels = $subjects;
			
			return $subjects;
		}
		catch(Exception $e){
			return array();
		}
	}
	
	function max($obsel1, $obsel2){
		if($obsel1 == null && $obsel2 == null){
			return null;
		} 
		elseif($obsel1 == null && $obsel2 != null){
			return $obsel2;
		}
		elseif($obsel2 == null && $obsel1 != null){
			return $obsel1;
		}
		// assume obsel1 != null and obsel2 != null
		if((int)$obsel2["hasBegin"][0]["value"]>(int)$obsel1["hasBegin"][0]["value"]){
			return $obsel2;
		}
		elseif((int)$obsel2["hasBegin"][0]["value"]==(int)$obsel1["hasBegin"][0]["value"]
			&& (int)$obsel2["hasEnd"][0]["value"]>(int)$obsel1["hasEnd"][0]["value"]){
			return $obsel2;
		}
		return $obsel1;
	}
	
	function getLastObsel($obseltype, $param1 = null, $param2 = null){
		// cache
		//Log::getInstance()->writeLog("obseltype: ".$obseltype." param1: ".$param1." param2: ".$param2);
		
		if($obseltype=="UserPresence"){
			$cache = new UserPresenceCache($this->uri, $obseltype, $param1, $param2);
			list($isOK,$retval) = $cache->read();
			if($isOK) return $retval;	
		}
		else if($obseltype=="CreatePushFeed"){
			$cache = new CreatePushFeedCache($this->uri, $obseltype, $param1);
			list($isOK,$retval) = $cache->read();
			if($isOK) return $retval;	
		}
		else if($obseltype=="CreatePullFeed"){
			$cache = new CreatePullFeedCache($this->uri, $obseltype, $param1);
			list($isOK,$retval) = $cache->read();
			if($isOK) return $retval;	
		}
		
		$obselOK = null;
		$obsels = $this->getObsels();
		foreach($obsels as $s => $obsel){
			# add the property uri for easily retrieving uri of obsel
			$obsel["uri"] = $s;
			
			if($obsel["type"][0]["value"]!=$obseltype) 
				continue;// if not match obsel type, omit
			switch($obseltype){
				case "UserPresence":
					if($obsel["user_id"][0]["value"]==$param1
						&& $obsel["session_id"][0]["value"]==$param2){
						$obselOK = $this->max($obselOK, $obsel);
					}
					break;
				case "CreatePushFeed":
					if($obsel["pushfeed_name"][0]["value"]==$param1){
						$obselOK = $this->max($obselOK, $obsel);
					}
					break;
				case "CreatePullFeed":
					if($obsel["pullfeed_name"][0]["value"]==$param1){
						$obselOK = $this->max($obselOK, $obsel);
					}
					break;
			}
			
		}
		if($obselOK == null){
			$obselOK = false;
		}
		
		// cache
		if($obseltype=="UserPresence"
			|| $obseltype=="CreatePushFeed"
			|| $obseltype=="CreatePullFeed"){
			$cache->write($obselOK);
		}
		return $obselOK;		
	}

	function getAbout($abr = true){
		try{
			// get about from KTBS
			$uri = $this->uri;
			$resource = new KtbsResource($uri);
			$subjects = $resource->getSubjects("@about",$abr,"json");
			
			$this->about = $subjects;
			return $subjects;
		}
		catch(Exception $e){
			return array();
		}
	}
}	
?>
