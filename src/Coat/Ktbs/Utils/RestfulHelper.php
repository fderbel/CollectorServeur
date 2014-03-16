<?php
namespace Coat\Ktbs\Utils;
use Coat\Ktbs\Utils\Log;

/* This class is used to interact with KTBS.*/
class RestfulHelper{
	
	static public function post_file_ktbs_by_curl($url,$filename){
				
		$file = file_get_contents($filename);
			
		$header = array("Content-type:text/turtle");
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $file);
		$reponse = curl_exec($curl);
		curl_close($curl);		
		
		echo $reponse;
	}
		
	static public function post($url,$content){
			
		$header = array("Content-type:text/turtle", "Expect:");
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
        curl_close($curl);
		
		$http_code = $infos["http_code"];
		//print_r($http_code)	; print_r($reponse)	;
		if($http_code == "201") return true; 
			else return $reponse;
	}		
	
	
	static public function put($url,$content){
					
		$header = array("Content-type:text/turtle");
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);
		
		$http_code = $infos["http_code"];
		//Log::writeRestfulLog("put", $url, $content, $http_code);
		if($http_code == "200") return true; else return false;
	}

	static public function get($url){
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);		
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);
		
		$http_code = $infos["http_code"];
		var_dump ($reponse);
		var_dump ($http_code);
		if($http_code == "200"|| $http_code == "303") return true; else return false;
	}

	static public function getEtag($url){
	
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);			
		$response = curl_exec($curl);
		$headers = curl_getinfo($curl);
		curl_close($curl);
		
		$pos = strpos($response,"ETag: ");
		if($pos<0) return false;
		$pos = $pos+strlen("ETag: '");
		$response1 = substr($response,$pos);
		$pos1 = strpos($response1,'"');
		$etag = substr($response1,0,$pos1);
		
		return $etag;
	}

	static public function getEtagAndPut($url,$content){
		$etag = RestfulHelper::getEtag($url);
		
		$header = array("Content-type:text/turtle",'If-match: "'.$etag.'"');
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		$reponse = curl_exec($curl);
		$infos = curl_getinfo($curl);
		curl_close($curl);
		
		$http_code = $infos["http_code"];
		//Log::writeRestfulLog("put", $url, $content, $http_code);
		if($http_code == "200") return true;
			else return $reponse;
	}
}
?>
