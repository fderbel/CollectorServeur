<?php
namespace Coat\Ktbs;
use Coat\Ktbs\Utils\RestfulHelper;
use \DateTime ;
use \DateTimeZone;
use Coat\Ktbs\Cache\KtbsResourceCache;
class Trace 
{
    
    public $base_uri = null;
	public $model_uri = null;
	public $name = null;	
	public $hasOrigin = null;
	public $uri = null ;
	
	function __construct($base_uri,$model_uri,$trace_Name)
	{	
	
		$this->base_uri = $base_uri;
		$this->name = $trace_Name."/";		
		$this->model_uri = $model_uri;
		$this->hasOrigin = $this->getTime();
		$this->uri = $base_uri.$this->name ;
	}
	
	function dump()
	{
		
		$prefixes[] = "@prefix : <http://liris.cnrs.fr/silex/2009/ktbs#> .";				
		
		$statements[] = "<> :contains <".$this->name."> .";
		$statements[] = "<".$this->name."> a :StoredTrace .";
		$statements[] = "<".$this->name."> :hasModel <".$this->model_uri."> .";		
		$statements[] = "<".$this->name."> :hasOrigin ".'"'.$this->hasOrigin.'"'." .";
		$statements[] = "<".$this->name."> :hasDefaultSubject ".'"'."trace for activity".'"'." .";
			
		
		
		$this->script = implode("\n", $prefixes)."\n"
					.implode("\n", $statements);
		
		$result = RestfulHelper::post($this->base_uri, $this->script);
		return $result;
	}
	
	function exist()
	{
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
	        //$this->exist = RestfulHelper::get($this->uri);
            //return $this->exist;
	}
	
	function getTime()
	
	{
		$datetime = new DateTime();
		
		$m = explode(' ',microtime());		
		$microSeconds = $m[0];
		$milliSeconds = (int)round($microSeconds*1000,3);
		$seconds = $m[1];
		
		$datetime->setTimezone(new DateTimeZone('UTC'));
		
		$now = $datetime->format('Y-m-d')."T".$datetime->format('H:i:s').".".str_pad($milliSeconds,3,"0",STR_PAD_LEFT)."Z";
		
		return $now;
	}	

}


