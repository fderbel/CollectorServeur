<?php
namespace Coat\Ktbs\Utils;
class UserParser{
	
	function __construct($user){
		$this->user = $user;
	}
	
	function getUniqueId(){
		if($this->user->mId == 0){
			return "0".$this->user->mName;
		}
		return $this->user->mId;
	}
}
