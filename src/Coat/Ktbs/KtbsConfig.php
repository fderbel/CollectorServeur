<?php
namespace Coat\Ktbs;

use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Entity\Workspace\Workspace;
use Coat\Ktbs\Base;
use Coat\Ktbs\Trace;
use Coat\Ktbs\TraceModel;
use Coat\Ktbs\KtbsRoot;

class KtbsConfig {

   
    private $exist=null;
    private $BaseName=null;
    private $trace_Name=null;
    private $modelName=null;
    private $ModelURI=null;
    private $TraceURI=null;
    private $BaseURI=null;

    function __construct (User $user,Workspace $workspace){
        $this->root = "http://ktbs.univ-lyon1.fr/" ;
       //$this->root = "http://localhost:8001/";
        //$this->root = "https://dsi-liris-silex.univ-lyon1.fr/protected/ktbs/";
        $this->BaseName     = $user->getUsername().$user->getId()."/";
        $this->BaseURI      = $this->root.$this->BaseName;
        $this->trace_Name   = str_replace(' ','',$workspace->getName()).$workspace->getCode();
        $this->TraceURI     = $this->root.$this->BaseName.$this->trace_Name."/";
        $this->modelName    = "model".$this->trace_Name;
        $this->ModelURI     =  $this->BaseURI.$this->modelName; 
    }

    function getTraceName(){
        return $this->trace_Name ;
    }
    
    function getBaseURI(){
        return $this->BaseURI ;
    }
    
    function getModelURI(){
        return $this->ModelURI ;
    }
    
    
    function createBase (){   
        $Base= new Base ($this->root,$this->BaseName) ;
        $Base->dump();
    }
    
     function createModel (){   
        $model = new TraceModel ($this->root.$this->BaseName, $this->modelName);
        $response = $model->dump();
        if (!$response)
            {$this->createBase ();$model->dump();}
    }
    
    function createTrace (){  
        $this->createModel ();
        $trace = new Trace ($this->BaseURI,$this->ModelURI,$this->trace_Name);
        $response = $trace->dump() ;
        if (!$response)
            {$this->createBase ();$trace->dump();}
    }
    
    function createObsel ($log,$user){
       
            $obsel = new ObselLogEvent($this->ModelURI,$this->TraceURI);
            $obsel->load($log,$user) ;
            $response = $obsel->dump() ;
              if (!$response)
            {$this->createTrace ();$obsel->dump() ;}
        
    }
    
 
}
?>
