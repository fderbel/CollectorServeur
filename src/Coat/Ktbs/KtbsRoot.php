<?php
namespace Coat\Ktbs;
use Coat\Ktbs\Utils\RestfulHelper;

class KtbsRoot{

	public $uri = null;
	public $label = null;
	
	function __construct($uri) {
		$this->uri = $uri;		
	}
	
	function exist(){
		
		
		$this->exist = RestfulHelper::get($this->uri);
		return $this->exist;
	}
	
}

?>
