<?php

namespace Coat\Ktbs;

use Coat\Ktbs\Utils\RestfulHelper;
use \DateTimeZone;
use Claroline\ForumBundle\Entity\Subject;

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
    public $ExerciceId = null;
    public $Exercisetitle=null;
    public	$ExerciseResult=null;
    public $BlogId=null;
    public $postBlogId=null;
    public	$blog_authorizeComment=null;
    public 	$blog_authorizeAnonymousComment=null;
    public	$blog_autoPublishPost=null;
    public	$blog_autoPublishComment =null;
    
    public function __construct($model_uri,$trace_uri){
		$this->model_uri = $model_uri;
		$this->trace_uri = $trace_uri;
	}	
    public function load($log){
	    
	    $this->name      ="S_".$log->getAction()."_".rand();	
        $this->uri       = $this->trace_uri.$this->name;
		$this->hasDate   = $log->getDateLog()->format('Y-m-d H:i:s');
		$this->Subject   ="Obsel of Trace : ".$this->trace_uri;
		$this->type      = $this->model_uri."#".$log->getAction();
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
		 if (array_key_exists ('old_title',$details['subject'])) {$this->subjectold_title = $details['subject']['old_title'];}
		 if (array_key_exists ('new_title',$details['subject'])) {$this->subjectNew_title = $details['subject']['new_title'];}
		}
		
		
		if (array_key_exists ('message',$details)) 
		{
		 $this->MessageId = $details['message']['id'];
		 if (array_key_exists ('old_content',$details['message'])) {$this->MessageOldContent = $details['message']['old_content'];}
		 
		 if (array_key_exists ('new_content',$details['message'])) {$this->Messagenew_content = $details['message']['new_content'];}
		}
		//post
		if (array_key_exists ('post',$details)) 
		{
		$this->postTitle = $details['post']['title'];
		//$this->postBlogId = $details['post']['blog'];
		
		}
		// post config
		if (array_key_exists ('blog',$details)) 
		{
		//var_dump ($details['blog']['changeSet']['authorizeComment'][0]);
		if (array_key_exists ('authorizeComment',$details['blog']['changeSet'])){ $this->blog_authorizeComment = ($details['blog']['changeSet']['authorizeComment'][1]) ? 'true' : 'false';}
		if (array_key_exists ('authorizeAnonymousComment',$details['blog']['changeSet'])){ $this->blog_authorizeAnonymousComment = ($details['blog']['changeSet']['authorizeAnonymousComment'][1]) ? 'true' : 'false';}
		if (array_key_exists ('autoPublishPost',$details['blog']['changeSet'])) {$this->blog_autoPublishPost = ($details['blog']['changeSet']['autoPublishPost'][1]) ? 'true' : 'false';}
		if (array_key_exists ('autoPublishComment',$details['blog']['changeSet'])){ $this->blog_autoPublishComment = ($details['blog']['changeSet']['autoPublishComment'][1]) ? 'true' : 'false';}
		
		
		$this->BlogId = $details['blog']['blog'];
		
		}
		
		// exercice
		
		if (array_key_exists ('exercise',$details)) 
		{
		$this->ExerciceId = $details['exercise']['id'];
		if (array_key_exists ('title',$details['exercise'])) {$this->Exercisetitle = $details['exercise']['title'];}
		}
		
		if (array_key_exists ('result',$details)) {$this->ExerciseResult = $details['result'];}
		
}

	public function dump(){
	
		$prefixes[] = "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .";
		$prefixes[] = "@prefix ktbs: <http://liris.cnrs.fr/silex/2009/ktbs#> .";
		$prefixes[] = "@prefix : <".$this->type."/> .";
		
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
		
		if ($this->subjectId) {
		$statements[] = "<".$this->name."> :hasTool_subjectId ".'"'.$this->subjectId.'"'." .";}
		if ($this->MessageId) {$statements[] = "<".$this->name."> :hasTool_MessageId ".'"'.$this->MessageId.'"'." .";}
		if ($this->subjectold_title) {$statements[] = "<".$this->name."> :hasTool_subjectOldContent ".'"'.$this->subjectold_title.'"'." .";}
		
		if ($this->subjectNew_title) {$statements[] = "<".$this->name."> :hasTool_subjectNew_title ".'"'.$this->subjectNew_title.'"'." .";}
		if ($this->MessageOldContent) {$statements[] = "<".$this->name."> :hasTool_MessageOldContent ".'"'.$this->MessageOldContent.'"'." .";}
		if ($this->Messagenew_content) {$statements[] = "<".$this->name."> :hasTool_Messagenew_content ".'"'.$this->Messagenew_content.'"'." .";}
		//blog
		if ($this->postTitle) {$statements[] = "<".$this->name."> :hasTool_Post_Title ".'"'.$this->postTitle.'"'." .";}
		//exercice
		if ($this->ExerciceId) {$statements[] = "<".$this->name."> :hasTool_Exercice_Id ".'"'.$this->ExerciceId.'"'." .";}
		if ($this->Exercisetitle) {$statements[] = "<".$this->name."> :hasTool_Exercise_Title ".'"'.$this->Exercisetitle.'"'." .";}
		if ($this->ExerciseResult) {$statements[] = "<".$this->name."> :hasTool_Exercise_Result ".'"'.$this->ExerciseResult.'"'." .";}
	    
	   	if ($this->BlogId) {$statements[] = "<".$this->name."> :hasTool_BlogId ".'"'.$this->BlogId.'"'." .";}
	   	if ($this->postBlogId) {$statements[] = "<".$this->name."> :hasTool_postBlogId ".'"'.$this->postBlogId.'"'." .";}
	    if ($this->blog_authorizeComment !== null) {$statements[] = "<".$this->name."> :hasTool_blog_authorizeComment ".'"'.$this->blog_authorizeComment.'"'." .";}
	   	if ($this->blog_authorizeAnonymousComment !== null) {$statements[] = "<".$this->name."> :hasTool_blog_authorizeAnonymousComment ".'"'.$this->blog_authorizeAnonymousComment.'"'." .";}
	    if ($this->blog_autoPublishPost !== null) {$statements[] = "<".$this->name."> :hasTool_blog_autoPublishPost ".'"'.$this->blog_autoPublishPost.'"'." .";}
		if ($this->blog_autoPublishComment !== null) {$statements[] = "<".$this->name."> :hasTool_blog_autoPublishComment ".'"'.$this->blog_autoPublishComment.'"'." .";}
	
		$this->script = implode("\n", $prefixes)."\n"
						.implode("\n", $statements);
		$this->numAttr = count($statements);
	    $result = RestfulHelper::post($this->trace_uri, $this->script);
	    return $result;
	}
}
?>
