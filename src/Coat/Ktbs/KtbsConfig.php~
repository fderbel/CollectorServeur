<?php
namespace Coat\Ktbs;

use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Coat\Ktbs\Base;
use Coat\Ktbs\Trace;
use Coat\Ktbs\TraceModel;
use Coat\Ktbs\KtbsRoot;

class KtbsConfig

{

    public $root=null ;	
    public $model=null;
    public $exist=null;

    function __construct ()
    {
        $this->root = "http://clacodev.univ-lyon1.fr:8080/" ;
        $this->model= "model1";
        $root = new KtbsRoot($this->root);
        if ( $root->exist() ) {$this->exist= true;}
        else $this->exist= false ;

    }

    function createBase (User $user)
    {   
        if ($this->exist)
        {
        $BaseName = $this->getBaseName ($user);
        $Base= new Base ($this->root,$BaseName) ;
        if ( !$Base->exist() ) {$Base->dump();} 
        $model = new TraceModel ($Base->uri, $this->model);
        if ( !$model->exist() ) {$model->dump();} 
        }
    }

    function createTrace ( User $user, AbstractWorkspace $workspace )	
    {  
       if ($this->exist)
        {
       $DataTrace = $this->DataTrace ($user);
       $trace_Name = $this->getTraceName ($workspace);
       $trace = new Trace ($DataTrace["base"],$DataTrace["model"],$trace_Name);
       $trace->dump() ;	
       }
    }	
    
    function createObsel (User $user, AbstractWorkspace $workspace , $log)	
    {
        if ($this->exist)
        {
        $DataObsel = $this->DataObsel ($user,$workspace);
        $obsel = new ObselLogEvent($DataObsel["model"],$DataObsel["trace"]);
        $obsel->load($log) ;
        $obsel->dump() ;
        }
    }	
    
   function getBaseName (User $user)
   {
         $BaseName = $user->getUsername().$user->getId()."/";
         return $BaseName ;
   }
   
   function getTraceName (AbstractWorkspace $workspace)
   {      if ($workspace->getName()==="Espace personnel") {$trace_Name="espacepersonnel".$workspace->getId();}
          else 
          $trace_Name = $workspace->getName().$workspace->getCode();
          return $trace_Name ;
   }
  
   function DataTrace (User $user)
   {
        $BaseName1 = $this->getBaseName ($user);
       // $BaseName1 = $user->getUsername().$user->getId()."/";
        $Base1= new Base ($this->root,$BaseName1) ;
        if ( !$Base1->exist() ) {$Base1->dump();}
        $model1 = new TraceModel ($Base1->uri, $this->model);
        if ( !$model1->exist() ) {$model1->dump();}

        return array( "base"=>$Base1->uri,"model"=>$model1->uri);
   } 
    
   function DataObsel (User $user, AbstractWorkspace $workspace)
   {
        $DataTrace1 = $this->DataTrace ($user);
        //$trace_Name1= $workspace->getName().$workspace->getId()."/";
        $trace_Name1 = $this -> getTraceName ($workspace);
        $trace1 = new Trace ($DataTrace1["base"],$DataTrace1["model"],$trace_Name1);
        if ( !$trace1->exist() ) {$trace1->dump();}
	
        return array("model"=>$DataTrace1["model"],"trace"=>$trace1->uri,"BaseURI"=>$DataTrace1["base"],"TraceName"=>"$trace_Name1");
   }  
 
}
?>
