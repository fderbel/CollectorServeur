<?php
namespace Coat\Ktbs;
require_once $collectra_class["db.CT_Channel"];

class KtbsConfig{	
	
	public static function getInstance(){
		return new KtbsConfig();
	}
	
	public function getKtbsRoot(){		
		global $wgKtbsRoot;		
		return $wgKtbsRoot;
	}

	public function getBaseName(){
		
		global $wgKtbsBase;		
		return $wgKtbsBase;		
	}
	
	public function getBaseURI(){
		$base_uri = $this->getKtbsRoot().$this->getBaseName();
		return $base_uri;		
	}

	public function getModelName(){
		
		global $wgKtbsModel;		
		return $wgKtbsModel;	
	}
	
	public function getModelURI(){
		$model_uri = $this->getBaseURI().$this->getModelName();
		return $model_uri;
	}

	public function getSites(){
		global $collectra_file;
				
		$ct_channel = new CT_Channel();
		$sites = $ct_channel->getSites();	
		return $sites;
	}
}
?>
