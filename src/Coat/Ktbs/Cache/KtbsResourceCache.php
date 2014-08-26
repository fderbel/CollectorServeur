<?php
namespace Coat\Ktbs\Cache;

class KtbsResourceCache{
	public $key;
	public $retval;
	public $expire;
	
	public function __construct($resource_uri){
	    
	    $key = implode(";",array($resource_uri));
		$this->cache_folder = dirname( __FILE__ )."/FilesCache";
		$this->expire_default = 7200;
		$this->key = $key;
		$this->key_md5 = get_class($this).md5($this->key);
		$this->data_file = $this->cache_folder."/".$this->key_md5.".data";
		$this->time_file = $this->cache_folder."/".$this->key_md5.".time";
	}
	
	public function write($data){
	
	    list($now_u,$now_s) = explode(" ", microtime());
	    $expire = $now_s + 3600;
				
		$this->data = serialize($data);
		$this->expire = $expire;
		
		$fp = fopen($this->data_file, 'w');						
		fwrite($fp, $this->data);
		fclose($fp);
		
		$fp = fopen($this->time_file, 'w');						
		fwrite($fp, $this->expire);
		fclose($fp);
	}
	
	public function read(){
				
		if(!file_exists($this->data_file)){
			return array(false, null);
		}
		
		$expire = file_get_contents($this->time_file);
		
		list($now_u, $now_s) = explode(" ", microtime());
		
		if($now_s > $expire){
			$this->clear();
			return array(false, null);
		}
		
		$data = file_get_contents($this->data_file);
		if($data===false){
			return array(false, null);
		}
		
		$this->data = $data;
		
		$retval = unserialize($data);
		
		return array(true, $retval);
	}
	
	public function clear(){
		unlink($this->data_file);
		unlink($this->time_file);
	}
}

?>

