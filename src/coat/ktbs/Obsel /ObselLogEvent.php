<?php

namespace Coat\Ktbs\Obsel;
use Coat\Ktbs\Utils\N3Parser;
use Coat\Ktbs\Trace\Trace;

class ObselLogEvent {
public $uri = null;	
public $name = null;	
public $type = null;
public $hasSuperObselType = null;
public $model_uri = null;
public $trace_uri = null;
	
public $hasBegin = null;
public $hasEnd = null;
public $action = null;
public $toolName = null;
public $IsDisplayedInAdmin = null;
public $IsDisplayedInWorkspace = null;
public $IsOwner = null;
public $IsDisplayedInWorkspace = null;


	public function __construct($model_uri,$trace_uri){
	echo " <script> console.log (\"obsel\")</script>";	
                $name = "eventLog_".rand();
		
		$this->name=$name;
		$this->uri=$trace_uri.$name;		
		$this->type = $model_uri."eventLog";
		$this->hasSuperObselType = null;
		$this->model_uri = $model_uri;
		$this->trace_uri = $trace_uri;
	}	
	
	public function load($log){
		
		$this->$action = $log->getAction();
                $this->$toolName = $log->getToolName();
                $this->$IsDisplayedInAdmin = $log->getIsDisplayedInAdmin();
                $this->$IsDisplayedInWorkspace = $log->getIsDisplayedInWorkspace();
                $this->$Owner = $log->getOwner();
		
	}

	public function dump(){
		$prefixes[] = "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .";
		$prefixes[] = "@prefix ktbs: <http://liris.cnrs.fr/silex/2009/ktbs#> .";
		$prefixes[] = "@prefix : <".$this->model_uri."> .";
				
		
		$statements[] = "<".$this->name."> a <".$this->type.">.";
		$statements[] = "<".$this->name."> ktbs:hasTrace <> .";		
		$statements[] = "<".$this->name."> :action ".'"'.$this->action.'"'." .";
		$statements[] = "<".$this->name."> :toolName ".'"'.$this->toolName.'"'." .";
		$statements[] = "<".$this->name."> :IsDisplayedInAdmin ".'"'.$this->IsDisplayedInAdmin.'"'." .";
                $statements[] = "<".$this->name."> :Owner ".'"'.$this->Owner.'"'." .";
		$this->script = implode("\n", $prefixes)."\n"
						.implode("\n", $statements);
		
		$this->numAttr = count($statements);
		$this->result = RestfulHelper::post($this->trace_uri, $this->script);
	}
}
?>
