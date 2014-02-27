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
public $ResourcePath=null ;
public $CategoryId = null ;
public $oldName = null ;
public $newName = null;
public $subjectId = null ;
public $MessageId = null ;
public $subjectold_title = null ;
public $subjectNew_title = null;
public $MessageOldContent = null;
public $Messagenew_content = null;
public $postTitle = null ;
public $ResourceNode =null;
public $ResourceType = null ;
public function __construct($model_uri,$trace_uri)
	{
		$this->model_uri = $model_uri."/";
		$this->trace_uri = $trace_uri;
	}	
public function load($log)
{
	    
	    $this->name      ="S_".$log->getAction()."_".rand();	
        $this->uri       = $this->trace_uri.$this->name;
		$this->hasDate   = $log->getDateLog()->format('Y-m-d H:i:s');
		$this->Subject   ="Obsel of Trace : ".$this->trace_uri;
		$this->type      = $this->model_uri.$log->getAction();
		// user information
		$this->UserID    =$log->getDoer()->getId();
		$this->UserName  =$log->getDoer()->getUsername();
		$this->FirstName =$log->getDoer()->getFirstName();
		$this->LastName  =$log->getDoer()->getLastName();
		// tool information
		$this->WorkspaceName = $log->getWorkspace()->getName();
		if ($log->getResourceNode() ) 
		{
		$this->ResourceNode = $log->getResourceNode()->getName();
		$this->ResourcePath = $log->getResourceNode()->getPathForDisplay();
		}
		if ($log->getResourceType() ) 
		{$this->ResourceType = $log->getResourceType()->getName();}
		$details= $log->getDetails();
		if (array_key_exists ('oldName',$details)) {$this->oldName = $details['oldName'];}
		if (array_key_exists ('newName',$details)) {$this->newName = $details['newName'];}
		if (array_key_exists ('category',$details)) {$this->CategoryId = $details['category']['id'];}
		if (array_key_exists ('subject',$details)) 
		{
		 $this->subjectId = $details['subject']['id'];
		 if ($details['subject']['old_title']) {$this->subjectold_title = $details['subject']['old_title'];}
		 if ($details['subject']['new_title']) {$this->subjectNew_title = $details['subject']['new_title'];}
		}
		
		
		if (array_key_exists ('message',$details)) 
		{
		 $this->MessageId = $details['message']['id'];
		 if ($details['message']['old_content']) {$this->MessageOldContent = $details['message']['old_content'];}
		 if ($details['message']['new_content']) {$this->Messagenew_content = $details['message']['new_content'];}
		}
		if (array_key_exists ('post',$details)) 
		{
		$this->postTitle = $details['post']['title'];
		
		}
}

	public function dump(){
	
		$prefixes[] = "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .";
		$prefixes[] = "@prefix ktbs: <http://liris.cnrs.fr/silex/2009/ktbs#> .";
		$prefixes[] = "@prefix : <".$this->model_uri."> .";
		
		$statements[] = "<".$this->name."> a <".$this->type.">.";
		$statements[] = "<".$this->name."> ktbs:hasTrace <> .";	
		$statements[] = "<".$this->name."> ktbs:hasSubject ".'"'.$this->Subject.'"'." .";
		$statements[] = "<".$this->name."> :hasDate ".'"'.$this->hasDate.'"'." .";
		//user information
		$statements[] = "<".$this->name."> :hasUser_UserID ".'"'.$this->UserID.'"'." .";
		$statements[] = "<".$this->name."> :hasUser_UserName ".'"'.$this->UserName.'"'." .";
		$statements[] = "<".$this->name."> :hasUser_FirstName ".'"'.$this->FirstName.'"'." .";
		$statements[] = "<".$this->name."> :hasUser_LastName ".'"'.$this->LastName.'"'." .";
		// tool information
	    if ($this->WorkspaceName) {$statements[] = "<".$this->name."> :hasTool_WorkspaceName ".'"'.$this->WorkspaceName.'"'." .";}
		if ($this->ResourceNode) {$statements[] = "<".$this->name."> :hasTool_ResourceName ".'"'.$this->ResourceNode.'"'." .";}
		if ($this->ResourcePath) {$statements[] = "<".$this->name."> :hasTool_ResourcePath ".'"'.$this->ResourcePath.'"'." .";}
		if ($this->ResourceType) {$statements[] = "<".$this->name."> :hasTool_ResourceType ".'"'.$this->ResourceType.'"'." .";}
		if ($this->oldName) {$statements[] = "<".$this->name."> :hasTool_OldName ".'"'.$this->oldName.'"'." .";}
		// forum
		if ($this->newName) {$statements[] = "<".$this->name."> :hasTool_NewName ".'"'.$this->newName.'"'." .";}
		if ($this->CategoryId) {$statements[] = "<".$this->name."> :hasTool_CategoryId ".'"'.$this->CategoryId.'"'." .";}
		
		if ($this->subjectId) {$statements[] = "<".$this->name."> :hasTool_subjectId ".'"'.$this->subjectId.'"'." .";}
		if ($this->MessageId) {$statements[] = "<".$this->name."> :hasTool_MessageId ".'"'.$this->MessageId.'"'." .";}
		if ($this->subjectold_title) {$statements[] = "<".$this->name."> :hasTool_subjectOldContent ".'"'.$this->subjectold_title.'"'." .";}
		
		if ($this->subjectNew_title) {$statements[] = "<".$this->name."> :hasTool_subjectNew_title ".'"'.$this->subjectNew_title.'"'." .";}
		if ($this->MessageOldContent) {$statements[] = "<".$this->name."> :hasTool_MessageOldContent ".'"'.$this->MessageOldContent.'"'." .";}
		if ($this->Messagenew_content) {$statements[] = "<".$this->name."> :hasTool_Messagenew_content ".'"'.$this->Messagenew_content.'"'." .";}
		//blog
		if ($this->postTitle) {$statements[] = "<".$this->name."> :hasTool_Post_Title ".'"'.$this->postTitle.'"'." .";}
		
	    $this->script = implode("\n", $prefixes)."\n"
						.implode("\n", $statements);
		$this->numAttr = count($statements);
	    $this->result = RestfulHelper::post($this->trace_uri, $this->script);
	}
}
?>
