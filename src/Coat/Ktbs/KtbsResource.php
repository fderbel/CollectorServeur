<?php
namespace Coat\Ktbs;
use Coat\Ktbs\Utils\RestfulHelper;
use Coat\Ktbs\Lib\SimpleRdfParser;
use Coat\Ktbs\Lib\Rdfapi-php\Api\Util\RdfUtil;
use Coat\Ktbs\Utils\URIParser;

class KtbsResource {
	
	function __construct($resource_uri){		
		$this->uri = $resource_uri;
	}
	
	function exist(){
		return RestfulHelper::get($this->uri);
	}
	/*
	 * getSubjects function return a simplied array of subjects 
	 * in which each subject is an array of its own objects 
	 * with the keys of the array being its own properties
	 * Ex: ret = array("uri of subject"=> array("uri of property","object or its uri"))
	 */
	function getSubjects($aspect = "",$abr = false,$extension = "rdf"){
		$uri = $this->uri;
		$curl = curl_init($uri.$aspect.".".$extension);
		curl_setopt($curl, CURLOPT_GET, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);		
		$data = curl_exec($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);
				
		if($extension=="rdf"){
			$parser = new KtbsRDFParser();
			$parser->load($data,$uri);
			$this->subjects = $parser->toSubjects($abr);
			return $this->subjects;
		}
		else if($extension=="json"){
			$parser = new KtbsJsonParser();
			$parser->load($data);
			$this->subjects = $parser->toSubjects($abr);
			return $this->subjects;
		}
	}	
}

class KtbsRDFParser{
	
	public function load($data,$uri){
		$this->data = $data;
		$this->uri = $uri;
	}
	
	public function toSubjects($abr = false){
		
		$parser = new SimpleRdfParser();
		$statements = $parser->string2statements($this->data, $this->uri);

		$list = array();
		foreach ($statements as $statement) {
				
			list($s,$p,$o) = $statement;
			
			if($abr==true){
				$parser = URIParser::getInstance();
				$s = $parser->getName($s);
				$p = $parser->getName($p);
				
				if($p == "type"){
					$o = $parser->getName($o);
				}
				$list[$s][$p] = $o;
			}else {				
				$list[$s][$p] = $o;
			}				
		}
		return $list;
	}
}

class KtbsJsonParser{
	
	public function load($data){
		$this->data = $data;	
	}
	
	public function toSubjects($abr = false){
		$subjects = json_decode($this->data,true);
		
		$list = array();
		
		if($abr == true){
			$parser = URIParser::getInstance();
			
			foreach ($subjects as $s => $po) {
				$s = $parser->getName($s);
				
				foreach ($po as $p => $oo) {
					$p = $parser->getName($p);
					
					foreach ($oo as $i => $o) {
						if($o["type"] == "uri"){
							$oo[$i]["value"] = $parser->getName($o["value"]);
						}
					}
					$list[$s][$p] = $oo;
				}
			}
		}
		else {		
			
			foreach ($subjects as $s => $po) {
				foreach ($po as $p => $oo) {
					$list[$s][$p] = $oo;
				}
			}
		}
		
		return $list;
	}
}

?>
