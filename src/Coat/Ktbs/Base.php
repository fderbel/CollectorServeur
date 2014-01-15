<?php
namespace Coat\Ktbs;
use Coat\Ktbs\Utils\RestfulHelper;


class Base {
	public $uri = null;
	public $name = null;
	public $label = null;	
	public $root = null;
	
	function __construct($root, $name){
	    
		$this->name = $name;
		$this->root = $root;
		$this->uri = $root.$name;
	}
	
	function dump(){
		$prefixes[] = "@prefix ktbs: <http://liris.cnrs.fr/silex/2009/ktbs#>.";
		$prefixes[] = "@prefix owl: <http://www.w3.org/2002/07/owl#>.";
		$prefixes[] = "@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>.";
		$prefixes[] = "@prefix rdfrest: <http://liris.cnrs.fr/silex/2009/rdfrest#>.";
		$prefixes[] = "@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>.";
		$prefixes[] = "@prefix xml: <http://www.w3.org/XML/1998/namespace>.";
		$prefixes[] = "@prefix xsd: <http://www.w3.org/2001/XMLSchema#>.";
				
		$statements[] = "<> ktbs:hasBase <".$this->name."> .";
		$statements[] = "<".$this->name."> a ktbs:Base .";		
		$statements[] = "<".$this->name."> rdfs:label ".'"'."trace base for user".'"'." .";		
		
		$script = 	implode("\n", $prefixes)."\n"
					.implode("\n", $statements);
		
		RestfulHelper::post($this->root, $script);	
	}
	function exist(){
        $this->exist = RestfulHelper::get($this->uri);
		
		return $this->exist;
	}
}
?>
