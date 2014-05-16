<?php
class Obfuscator extends CCTemplate
{
	// encodes every character using URL encoding (%hh)
	function EncodeEmail($str) {
		$retVal = '';
		$length = strlen($str);
		for ($i=0; $i<$length; $i++) $retVal.=sprintf('%%%X', ord($str[$i]));
		return $retVal;
	}
	// encodes every character into HTML character references (&#xhh;)
	function EncodeMailto($str) {
		$retVal = '';
		$length = strlen($str);
		for ($i=0; $i<$length; $i++) $retVal.=sprintf('&#x%X;', ord($str[$i]));
		return $retVal;
	}
}
?>