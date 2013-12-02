<?php
namespace Coat\ktbs\ClockEngine;
class CollectraClock{
	
	public static function getNow(){
		$datetime = new DateTime();
		
		$m = explode(' ',microtime());		
		$microSeconds = $m[0];
		$milliSeconds = (int)round($microSeconds*1000,3);
		$seconds = $m[1];
		
		$datetime->setTimezone(new DateTimeZone('UTC'));
		
		$now = $datetime->format('Y-m-d')."T".$datetime->format('H:i:s').".".str_pad($milliSeconds,3,"0",STR_PAD_LEFT)."Z";
		
		return $now;
	}	

	
}	
?>
