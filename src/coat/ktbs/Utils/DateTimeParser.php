<?php
namespace Coat\Ktbs\Utils;
class DateTimeParser{
	
	public function loadDT($datetime){
		$datetime->setTimezone(new DateTimeZone('UTC'));
		$str = $datetime->format("Y-m-d H:i:s");
		
		$this->year = (int)substr($str,0,4);
		$this->month = (int)substr($str,5,2);
		$this->day = (int)substr($str,8,2);
		$this->hour = (int)substr($str,11,2);
		$this->minute = (int)substr($str,14,2);
		$this->second = (int)substr($str,17,2);
		$this->millisecond = 0;
		$this->microsecond = 0;
		
		return $this;
	}
	
	public function load_microtime($microtime){
		$m = explode(' ',microtime());
		$totalSeconds = $m[1];
		$extraMilliseconds = (int)round($m[0]*1000,3);
		
		$str = substr(gmdate("c", $totalSeconds),0,19).".".str_pad($extraMilliseconds,3,"0",STR_PAD_LEFT)."";
		
		$this->year = (int)substr($str,0,4);
		$this->month = (int)substr($str,5,2);
		$this->day = (int)substr($str,8,2);
		$this->hour = (int)substr($str,11,2);
		$this->minute = (int)substr($str,14,2);
		$this->second = (int)substr($str,17,2);
		$this->millisecond = (int)substr($str,20,3);
		$this->microsecond = 0;
		
		return $this;
	}
	
	public function toXSDDateTime(){
		$yyyy = str_pad($this->year, 4, '0', STR_PAD_LEFT);
		$m = str_pad($this->month, 2, '0', STR_PAD_LEFT);
		$d = str_pad($this->day, 2, '0', STR_PAD_LEFT);
		$HH = str_pad($this->hour, 2, '0', STR_PAD_LEFT);
		$mm = str_pad($this->minute, 2, '0', STR_PAD_LEFT);
		$ss = str_pad($this->second, 2, '0', STR_PAD_LEFT);
		$sss = str_pad($this->millisecond, 3, '0', STR_PAD_LEFT);
		
		$date_f = $yyyy."-".$m."-".$d."T".$HH.":".$mm.":".$ss.".".$sss."Z";
		
		return $date_f;
	}
	
	public function toHtml(){
		
		$yyyy = str_pad($this->year, 4, '0', STR_PAD_LEFT);
		$m = str_pad($this->month, 2, '0', STR_PAD_LEFT);
		$d = str_pad($this->day, 2, '0', STR_PAD_LEFT);
		$HH = str_pad($this->hour, 2, '0', STR_PAD_LEFT);
		$mm = str_pad($this->minute, 2, '0', STR_PAD_LEFT);
		$ss = str_pad($this->second, 2, '0', STR_PAD_LEFT);
		$sss = str_pad($this->millisecond, 3, '0', STR_PAD_LEFT);
		
		$date_f = $yyyy."-".$m."-".$d." ".$HH.":".$mm.":".$ss.".".$sss;
		
		return $date_f;
	}
}

class XSDDateTimeParser{
	
	public function load($str){
		
		$this->year = (int)substr($str,0,4);
		$this->month = (int)substr($str,5,2);
		$this->day = (int)substr($str,8,2);
		$this->hour = (int)substr($str,11,2);
		$this->minute = (int)substr($str,14,2);
		$this->second = (int)substr($str,17,2);
		$this->millisecond = (int)substr($str,20,3);
		
		return $this;
	}
	// in: yyyy-MM-ddTHH:mm:ss.sssZ
	// out: yyyy-MM-dd HH:mm:ss.sss 
	public function toHtml(){
		
		$yyyy = str_pad($this->year, 4, '0', STR_PAD_LEFT);
		$m = str_pad($this->month, 2, '0', STR_PAD_LEFT);
		$d = str_pad($this->day, 2, '0', STR_PAD_LEFT);
		$HH = str_pad($this->hour, 2, '0', STR_PAD_LEFT);
		$mm = str_pad($this->minute, 2, '0', STR_PAD_LEFT);
		$ss = str_pad($this->second, 2, '0', STR_PAD_LEFT);
		$sss = str_pad($this->millisecond, 3, '0', STR_PAD_LEFT);
		
		$date_f = $yyyy."-".$m."-".$d." ".$HH.":".$mm.":".$ss.".".$sss;
		
		return $date_f;
	}
	// in: yyyy-MM-ddTHH:mm:ss.sssZ
	// out: yyyy-MM-dd HH:mm or HH:mm:ss 
	public function toShortHtml(){
		
		$now = new DateTime();
		$now->setTimezone(new DateTimeZone('UTC'));
		$now_f = $now->format("Y-m-d H:i:s");		
		
		$yyyy = str_pad($this->year, 4, '0', STR_PAD_LEFT);
		$m = str_pad($this->month, 2, '0', STR_PAD_LEFT);
		$d = str_pad($this->day, 2, '0', STR_PAD_LEFT);
		$HH = str_pad($this->hour, 2, '0', STR_PAD_LEFT);
		$mm = str_pad($this->minute, 2, '0', STR_PAD_LEFT);
		$ss = str_pad($this->second, 2, '0', STR_PAD_LEFT);
		$sss = str_pad($this->millisecond, 3, '0', STR_PAD_LEFT);
		
		$date_f = $yyyy."-".$m."-".$d." ".$HH.":".$mm.":".$ss.".".$sss;		
		
		if(substr($now_f,0,11)==substr($date_f,0,11)){
			return "".$HH.":".$mm.":".$ss;
		}
		else{
			return $yyyy."-".$m."-".$d." ".$HH.":".$mm;
		}		
	}
	
	public function tryParse($str){
		try{
			$yyyy = substr($str,0,4);
			if(!is_numeric($yyyy)) return false;			
			$m = substr($str,5,2);
			if(!is_numeric($m)) return false;
			$d = substr($str,8,2);
			if(!is_numeric($d)) return false;
			$HH = substr($str,11,2);
			if(!is_numeric($HH)) return false;
			$mm = substr($str,14,2);
			if(!is_numeric($mm)) return false;
			$ss = substr($str,17,2);
			if(!is_numeric($ss)) return false;
			$sss = substr($str,20,3);
			if(!is_numeric($sss)) return false;
						
			return true;
		}
		catch(Exception $e){
			
		}
		return false;
	}
}

?>
