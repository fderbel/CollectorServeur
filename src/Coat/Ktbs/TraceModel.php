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
	
	function dump()
	{
	$prefixes[] = "@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>.";
	$prefixes[] = "@prefix : <http://liris.cnrs.fr/silex/2009/ktbs#>.";	
	$statements[] = "<> :contains <".$this->name.">.";
    $statements[] = "<".$this->name."> a :TraceModel.";		
	$statements[] = "<".$this->name."> rdfs:label ".'"'."An example model".'"'." .";		
	$script = 	implode("\n", $prefixes)."\n"
					.implode("\n", $statements);
		
		RestfulHelper::post($this->base_uri, $script);	
	}
	
	function exist()
	{
	$this->exist = RestfulHelper::get($this->uri);
    return $this->exist;
	}
		
	
}
?>
