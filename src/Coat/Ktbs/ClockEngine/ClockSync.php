<?php
namespace Coat\ktbs\ClockEngine;
class ClockSync{
	
	function __construct($send_time_int, $received_microtime){
		$this->send_time_int = $send_time_int;
		
		$m = explode(' ',$received_microtime);
		$received_secs = $m[1];
		$received_millisecs = (int)round($m[0]*1000,3);
		
		$this->received_secs = $received_secs;
		$this->received_millisecs = $received_millisecs;
	}
	
	public function getServerTime($client_time_int){
		$this->client_time_int = $client_time_int;
		$diff_millisecs = $this->received_millisecs + (int)$this->client_time_int - (int)$this->send_time_int;
		$diff_secs = (int) ($diff_millisecs / 1000);
		$diff_millisecs = (int) $diff_millisecs % 1000;
		
		if($diff_millisecs < 0) {
			$diff_secs = $diff_secs - 1;
			$diff_millisecs = $diff_millisecs + 1000;
		}
		
		$this->server_secs = $this->received_secs + $diff_secs;
		$this->server_millisecs = $diff_millisecs;
				
		$this->server_time_dt = substr(gmdate("c", $this->server_secs),0,19).".".str_pad($this->server_millisecs,3,"0",STR_PAD_LEFT)."Z";
		
		return $this->server_time_dt;
	}
	
}
