<?php
namespace Coat\Ktbs\Utils;
use Coat\Ktbs\Utils\Log;

class Minuterie{
	public $deliminate = ";";
	
	public function __construct(){
	}
	
	public function start() {
		$m = explode(' ',microtime());
		$this->start_seconds = $m[1];
		$this->start_microseconds = $m[0];
	}
	
	public function load($obsel){		
	}
	
	public function saveMessage(){
		$this->start_str = substr(date("c", $this->start_seconds),0,19);
		$arr = array($this->start_str
						,$this->action
						,$this->general_type
						,$this->concrete_type
						,$this->uri				
						,$this->script_len
						,$this->attr_num
						,$this->result
						,$this->obsel_num
						,$this->response_time);
		$this->message = implode($this->deliminate, $arr);
		$log = Log::getInstance()->writeExperimentation($this->message);
	}

	public function getResponseTime(){
		
		$m = explode(' ',microtime(true));
		$this->finish_seconds = $m[1];
		$this->finish_microseconds = $m[0];
		
		$total_miliseconds = ((float)$this->finish_seconds - (float)$this->start_seconds) 
							+ ((float)$this->finish_microseconds - (float)$this->start_microseconds);
		
		$this->response_time = $total_miliseconds."";
		return $this->response_time;
	}
	
	public function finish(){
		$this->getResponseTime();
		$this->saveMessage();
	}
}

class ObselMinuterie extends Minuterie {
	
	public function __construct($action, $obsel){		
		$this->action = $action;
		$this->general_type = "Obsel";
		$this->concrete_type = $obsel;
	}
	
	public function load($obsel){
		$this->script_len = strlen($obsel->script);
		$this->result = str_replace("\n", " ", $obsel->result);
		$this->uri = $obsel->uri;
		$this->attr_num = $obsel->numAttr;
	}
}

class RTraceMinuterie extends Minuterie {
	
	public function __construct($action, $trace){		
		$this->action = $action;
		$this->general_type = "Trace";
		$this->concrete_type = $trace;		
	}
	
	public function load($trace,$subjects){
		$this->obsel_num = count($subjects);		
		$this->uri = $trace->uri;
	}
}

class CTraceMinuterie extends Minuterie {
	
	public function __construct($action, $trace){		
		$this->action = $action;
		$this->general_type = "Trace";
		$this->concrete_type = $trace;
	}
	
	public function load($trace){				
		$this->uri = $trace->uri;
		$this->result = str_replace("\n", " ", $trace->result);
	}
}