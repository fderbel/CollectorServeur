<?php

namespace Coat\Ktbs;

use Coat\Ktbs\Utils\N3Parser;
use Coat\Ktbs\Utils\RestfulHelper;
use \DateTimeZone;

class ObselLogEvent {
public $uri = null;	
public $name = null;	
public $type = null;
public $hasSuperObselType = null;
public $model_uri = null;
public $trace_uri = null;
public $hasBegin = null;
public $hasEnd = null;
public $Subject = null;
public $UserID = null;
public $UserName = null;
public $FirstName = null;
public $LastName = null;
public $ToolName = null;
public $ToolType = null;


public $action = null;
public $toolName = null;
public $IsDisplayedInAdmin = null;
public $IsDisplayedInWorkspace = null;
public $IsOwner = null;
public $doer = null ;
public $DoerType=null;
public $DoerIp=null;
public $ReceiverGroup=null;
public $Workspace=null;
public $ResourceNode=null;
public $Role=null;
public $DoerPlatformRole=null;
public $DoerWorkspaceRole=null;
public $ResourceType=null;



	public function __construct($model_uri,$trace_uri)
	{
		$this->model_uri = $model_uri;
		$this->trace_uri = $trace_uri;
	}	
	
	public function load($log){
	
	$m = explode(' ',microtime());$microSeconds = $m[0];$milliSeconds = (int)round($microSeconds*1000,3);$seconds = $m[1];
		
		$log->getDateLog()->setTimezone(new DateTimeZone('UTC'));
		
		 
		
        $this->name      ="S_".$log->getAction().rand();	
        $this->uri       = $this->trace_uri.$this->name;
		$this->hasBegin  =$log->getDateLog()->format('Y-m-d')."T".$log->getDateLog()->format('H:i:s').".".str_pad($milliSeconds,3,"0",STR_PAD_LEFT)."Z";
		$this->hasEnd    =$log->getDateLog()->format('Y-m-d')."T".$log->getDateLog()->format('H:i:s').".".str_pad($milliSeconds,3,"0",STR_PAD_LEFT)."Z";
		$this->Subject   ="Obsel of Action : ".$log->getAction();
		$this->type      = $this->model_uri.$log->getAction();
		// user information
		$this->UserID    =$log->getDoer()->getId();
		$this->UserName  =$log->getDoer()->getUsername();
		$this->FirstName =$log->getDoer()->getFirstName();
		$this->LastName  =$log->getDoer()->getLastName();
		// tool information
		$details = $log->getDetails();
		$this->ToolName =$details['doer']['workspaceRoles'];
		//$this->ToolType = $log->getResourceType();
		
		
		
		
		
		
		
		$this->action = $log->getAction();
        $this->toolName = $log->getToolName();
        $this->IsDisplayedInAdmin = $log->isDisplayedInAdmin();
        $this->IsDisplayedInWorkspace = $log->isDisplayedInWorkspace();
        $this->Owner = $log->getOwner();
        $this->doer = $log->getDoer()->getUsername();
        $this->DoerType = $log->getDoerType();
        $this->DoerIp = $log->getDoerIp();
        $this->ReceiverGroup = $log->getReceiverGroup();
        $this->Workspace = $log->getWorkspace();
        $this->ResourceNode = $log->getResourceNode();
        $this->Role = $log->getRole();
        $this->DoerPlatformRole = $log->getDoerPlatformRoles();
        $this->DoerWorkspaceRole = $log->getDoerWorkspaceRoles();
        $this->ResourceType = $log->getResourceType();
		
	}

	public function dump(){
	
		$prefixes[] = "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .";
		$prefixes[] = "@prefix ktbs: <http://liris.cnrs.fr/silex/2009/ktbs#> .";
		$prefixes[] = "@prefix : <".$this->model_uri."> .";
		
		$statements[] = "<".$this->name."> a <".$this->type.">.";
		$statements[] = "<".$this->name."> ktbs:hasTrace <> .";	
	//	$statements[] = "<".$this->name."> ktbs:hasBegin ".'"'.$this->hasBegin.'"'."^^xsd:dateTime .";
	//	$statements[] = "<".$this->name."> ktbs:hasEnd ".'"'.$this->hasEnd.'"'."^^xsd:dateTime .";
		$statements[] = "<".$this->name."> ktbs:hasSubject ".'"'.$this->Subject.'"'." .";
		//user information
		$statements[] = "<".$this->name."> :hasUser_UserID ".'"'.$this->UserID.'"'." .";
		$statements[] = "<".$this->name."> :hasUser_UserName ".'"'.$this->UserName.'"'." .";
		$statements[] = "<".$this->name."> :hasUser_FirstName ".'"'.$this->FirstName.'"'." .";
		$statements[] = "<".$this->name."> :hasUser_LastName ".'"'.$this->LastName.'"'." .";
		// tool information
		$statements[] = "<".$this->name."> :hasTool_ToolName ".'"'.$this->ToolName.'"'." .";
		$statements[] = "<".$this->name."> :hasTool_ToolType ".'"'.$this->ToolType.'"'." .";
		
		
			
		$statements[] = "<".$this->name."> :action ".'"'.$this->action.'"'." .";
		$statements[] = "<".$this->name."> :toolName ".'"'.$this->toolName.'"'." .";
		$statements[] = "<".$this->name."> :IsDisplayedInAdmin ".'"'.$this->IsDisplayedInAdmin.'"'." .";
        $statements[] = "<".$this->name."> :ReceiverGroup ".'"'.$this->ReceiverGroup.'"'." .";
       // $statements[] = "<".$this->name."> :Workspace ".'"'.$this->Workspace.'"'." .";
       // $statements[] = "<".$this->name."> :ResourceNode ".'"'.$this->ResourceNode.'"'." .";
       // $statements[] = "<".$this->name."> :Role ".'"'.$this->Role.'"'." .";
        //$statements[] = "<".$this->name."> :Owner ".'"'.$this->Owner.'"'." .";
        //$statements[] = "<".$this->name."> :DoerPlatformRole ".'"'.$this->DoerPlatformRole.'"'." .";
       // $statements[] = "<".$this->name."> :DoerWorkspaceRole ".'"'.$this->DoerWorkspaceRole.'"'." .";
      //  $statements[] = "<".$this->name."> :ResourceType ".'"'.$this->ResourceType.'"'." .";
       $statements[] = "<".$this->name."> :username".'"'.$this->doer.'"'." .";
               
       
       
       
       // $statements[] = "<".$this->name."> :doer ".'"'.$this->doer.'"'." .";
		

		$this->script = implode("\n", $prefixes)."\n"
						.implode("\n", $statements);
		
		$this->numAttr = count($statements);
		
		
		$this->result = RestfulHelper::post($this->trace_uri, $this->script);
	}
}
?>
