<?php
namespace Coat\Ktbs\Utils;
	class N3Parser{
				
		public static function encode($str) {
			$quotation_commas = '"""';
			$ret = $str;
			$ret = str_replace("\\", "\\\\", $ret);
			$ret = str_replace('"', '\\"', $ret);			
			$ret = str_replace("\n", "\\n", $ret);
			$ret = str_replace("\t", "\\t", $ret);
			$ret = $quotation_commas.$ret.$quotation_commas;
			return $ret;
		}	
	}
?>
