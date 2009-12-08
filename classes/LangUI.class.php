<?php
define( "UPPER", 1 );
define( "LOWER", 2 );
define( "UPPERFIRST", 3 );
/**
	the Language User Interface object. This class just handles the translation of strings
*/
class LangUI {
	// language constants
	var $m_baseLang = 'en';
	var $m_localLang = 'en';
	var $m_encoding = 'UTF-8';
	var $m_langArray = NULL;
	var $m_langs = array(
		'en' => 'English', 
		'it' => 'Italian',
		'fr' => 'French',
		'sv' => 'Swedish',
		'da' => 'Danish',
		'de' => 'German'
	);
	
	function LangUI() {
		// empty class constructor
	}
	
	/** clean up variables that are not to be stored as a session */
	function cleanUp() {	
		$this->m_langArray = NULL;
	}

	/** language handling */
	function setLocalLang( $lang ) {
		$this->m_localLang = $lang;
	}

	/** Gets the local language */
	function getLocalLang() {
		return $this->m_localLang;
	}
	
	function setEncoding($enc) 
	{
		if ($enc != null && $enc != "")
			$this->m_encoding = $enc;
	}
	
	function getEncoding()
	{
		return ($this->m_encoding);
	}

	/** set the lang array for translations */
	function setLangArray( $larr ) {
		$this->m_langArray =& $larr;
	}
	
	/** Returns a list of support languages */
	function getSupportedLangs() {
		return $this->m_langs;
	}
	
	/**
		Translate string to the local language [same form as the gettext abbreviation]
		This is the order of precedence:
		If the key exists in the lang array, return the value of the key
		If no key exists and the base lang is the same as the local lang, just return the string
		If this is not the base lang, then return string with a red star appended to show
		that a translation is required.
	*/
	function _( $str, $case=0 ) {
		$x = @$this->m_langArray[$str];
		if ($x) {
			$str = $x;
		} else if( $this->m_baseLang != $this->m_localLang ) {
			$str .= "<font color=red><b>*</b></font>";
		}
		switch ($case) {
			case UPPER:
				$str = strtoupper( $str );
				break;
			case LOWER:
				$str = strtolower( $str );
				break;
			case UPPERFIRST:
				break;
		}
		return (htmlspecialchars($str, ENT_QUOTES));
	}
}
?>