<?php
namespace Coat\Ktbs\Utils;
	class Log{
				
		public function writeCollectedTrace($event,$text) {
			
			global $wgUser;
			
			if(session_id() == null){
				session_start();				
			}
			$sessionID = session_id();
			
			$currentTime = date(DATE_RFC822);
			
			//$fp = fopen('./extensions/CollectTrace/collectedTrace.txt', 'a');
			
			// //fwrite($fp, "------- [User = ".",SessionID = ".$sessionID.",Event = ".$event.",Record Time = ".$currentTime."]-----------------------------\n");
			//fwrite($fp, "-------[User = ".$wgUser->getName().",SessionID = ".$sessionID.",Event = ".$event.",Record Time = ".$currentTime."]-------\n");
			//fwrite($fp, $text."\n");
			
			//fclose($fp);
		}	
	
		public function writeRestfulLog($method,$url,$content,$response_status){
			
			$currentTime = date(DATE_RFC822);
			
			//$fp = fopen('./extensions/Collectra/files/restful.log', 'a');
						
			//fwrite($fp, "".$currentTime." url='".$url."' method=".$method." => ".$response_status."\n");
			//fwrite($fp, " == content =\n");
			//fwrite($fp, "".$content."\n == end content ==\n");
			//fclose($fp);
		}
	
		public function writeLog($message){
			//$fp = fopen('./extensions/Collectra/files/debug.log', 'a');
						
			//fwrite($fp, "".$message."\n");
			//fclose($fp);
		}
		
		public function writeExperimentation($message){
			global $wgSitename;
			
			//$fp = fopen('./extensions/Collectra/files/exper'.$wgSitename.'.log', 'a');
						
			//fwrite($fp, "".$message."\n");
			//fclose($fp);
		}
		
		public static function getInstance(){
			return new Log();
		}
	}
