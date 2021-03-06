<?php
namespace Kvitli;

abstract class StorageFormatEntity {
	abstract function get_storage_format();

	function toString() {
		return $this->get_storage_format();
	}

	function __toString() {
		return $this->get_storage_format();
	}

	function pretty_print() {
		// attribution http://stackoverflow.com/a/7453922
		$xml = $this->get_storage_format();
		$xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
		$token      = strtok($xml, "\n");
		$result     = '';
		$pad        = 0;
		$matches    = array();
		while ($token !== false) :
			if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
				$indent=0;
			elseif (preg_match('/^<\/\w/', $token, $matches)) :
				$pad--;
				$indent = 0;
			elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
				$indent=1;
			else :
				$indent = 0;
			endif;
			$line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
			$result .= $line . "\n";
			$token   = strtok("\n");
			$pad    += $indent;
		endwhile;
		return $result;
	}
}
