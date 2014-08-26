<?php
namespace Coat\Ktbs;

use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Entity\Workspace\AbstractWorkspace;
use Coat\Ktbs\Base;
use Coat\Ktbs\Trace;
use Coat\Ktbs\TraceModel;
use Coat\Ktbs\KtbsRoot;

class KtbsConfig {

    public $root=null ;
    public $model=null;
    public $exist=null;
    public $BaseName=null;
    public $trace_Name=null;
    public $DataTrace=null;
    public $DataObsel=null;
    public $modelName=null;

    function __construct (User $user,AbstractWorkspace $workspace){
        $this->root = "http://ktbs.univ-lyon1.fr/" ;
        $root = new KtbsRoot($this->root);
        if ( $root->exist() ) 
            {$this->exist= true;}
        else 
            $this->exist= false ;
        $this->BaseName = $this->getBaseName ($user);
        $this->trace_Name = str_replace(' ','',$workspace->getName()).$workspace->getCode();
        $this->modelName ="model".$this->trace_Name;
        $this->DataTrace = $this->DataTrace ($user);
        $this->DataObsel = $this->DataObsel ($user,$workspace);
        if ($this->exist) {
            $this->createBase();
            $this->createModel();
            $this->createTrace();
        }
    }

    function createBase (){   
        $Base= new Base ($this->root,$this->BaseName) ;
        if ( !$Base->exist() ) {$Base->dump();} 
    }
    
     function createModel (){   
        $model = new TraceModel ($this->root.$this->BaseName, $this->modelName);
        if ( !$model->exist() ) {$model->dump();}
    }
    
    function createTrace (){  
        $trace = new Trace ($this->DataTrace["baseURI"],$this->DataTrace["modelURI"],$this->trace_Name);
        if ( !$trace->exist() ) {$trace->dump() ;}
    }
    
    function createObsel ($log){
        if ($this->exist) 
        {
            $obsel = new ObselLogEvent($this->DataObsel["modelURI"],$this->DataObsel["traceURI"]);
            $obsel->load($log) ;
            $obsel->dump() ;
        }
    }
    
   function getBaseName (User $user){
        $BaseName = $user->getUsername().$user->getId()."/";
        return $BaseName ;
   }
   
   function getTraceName (AbstractWorkspace $workspace){     
        $trace_Name = str_replace(' ','',$workspace->getName()).$workspace->getCode();
        return $trace_Name ;
   }
  
   function DataTrace (User $user){
        $BaseName = $this->BaseName;
        $BaseURI= $this->root.$BaseName;
        $modelName ="model".$this->trace_Name;
        $ModelURI= $this->root.$modelName; 
        return array( "baseURI"=>$BaseURI,"modelURI"=>$ModelURI);
   } 
    
   function DataObsel (User $user, AbstractWorkspace $workspace){
        $DataTrace = $this->DataTrace ($user);
        $TraceURI= $this->root.$this->BaseName.$this->trace_Name."/";
        return array("modelURI"=>$DataTrace["modelURI"],"traceURI"=>$TraceURI,"BaseURI"=>$DataTrace["baseURI"],"TraceName"=>$this->trace_Name);
   }  
 
}
?>
