<?php
//#!/usr/bin/php -q
//echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
/*-------------------------vyházení veškerých HTML tagů-----------------------------------*/
function strip_html_tags( $text )
{
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
          // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text );
    return strip_tags( $text );
}
/*-------------------------ořez na počet slov-----------------------------------*/
function oriznout_na_pocet_slov($string, $count){
  $words = explode(' ', $string);
  if (count($words) > $count){
    array_splice($words, $count);
    $string = implode(' ', $words);
  }
  return $string;
}
/*-------------------------náhodná IP, zatím nepoužito-----------------------------------*/
function randomip()
{
   $rand = array();
   for($i = 0; $i < 4; $i++)
   {
      $rand[] = rand(1,255);
   }
   $ip = "$rand[0].$rand[1].$rand[2].$rand[3]";
   return($ip);
}
/*-------------------------funkce, která přinutí echa, aby se ukázala v prohlížeči-----------------------------------*/
function buffer_flush(){

    echo str_pad('', 512);
    echo '<!-- -->';

    if(ob_get_length()){

        @ob_flush();
        @flush();
        @ob_end_flush();

    }

    @ob_start();

}
/*-------------------------čas běhu celého skriptu-----------------------------------*/
$start[6] = getTime();
/*-------------------------funkce kontroluje jestli je číslo násobkem čísla-----------------------------------*/

function je_nasobek ($cislo, $jakym_cislem)
{
if($cislo%$jakym_cislem == 0) return true;
else return false;
}

/*-------------------------require PHPmail-----------------------------------*/

//z prohlížeče: require '../PHPMailer_v5.1/class.phpmailer.php';
require '/home/domeny/domain.cz/web/subdomeny/www/wp-content/plugins/xml-parser/PHPMailer_v5.1/class.phpmailer.php';

/*-------------------------includování XML-RPC klienta-----------------------------------*/

//z prohlížeče: include ('../../../../wp-includes/class-IXR.php');
include ('/home/domeny/domain.cz/web/subdomeny/www/wp-includes/class-IXR.php');
$client = new IXR_Client('http://www.domain.cz/xmlrpc.php');
//-------------------------------třída překladače---------------------------------------------------------------------------------------------------
/**
* GTranslate - A class to comunicate with Google Translate(TM) Service
*               Google Translate(TM) API Wrapper
*               More info about Google(TM) service can be found on http://code.google.com/apis/ajaxlanguage/documentation/reference.html
*               This code has o affiliation with Google (TM) , its a PHP Library that allows to comunicate with public a API
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* @author Jose da Silva <jose@josedasilva.net>
* @since 2009/11/18
* @version 0.7.4
* @licence LGPL v3
*
* <code>
* <?
* require_once("GTranslate.php");
* try{
*       $gt = new Gtranslate;
*       echo $gt->english_to_german("hello world");
* } catch (GTranslateException $ge)
* {
*       echo $ge->getMessage();
* }
* ?>
* </code>
*/


/**
* Exception class for GTranslated Exceptions
*/

class GTranslateException extends Exception
{
        public function __construct($string) {
                parent::__construct($string, 0);
        }

        public function __toString() {
                return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }
}

class GTranslate
{
        /**
        * Google Translate(TM) Api endpoint
        * @access private
        * @var String
        */
        private $url = "http://ajax.googleapis.com/ajax/services/language/translate";

        /**
        * Google Translate (TM) Api Version
        * @access private
        * @var String
        */
        private $api_version = "2.0";

        /**
        * Comunication Transport Method
        * Available: http / curl
        * @access private
        * @var String
        */
        private $request_type = "http";

        /**
        * Path to available languages file
        * @access private
        * @var String
        */
        private $available_languages_file       = "languages.ini";

        /**
        * Holder to the parse of the ini file
        * @access private
        * @var Array
        */
        private $available_languages = array();

        /**
        * Google Translate api key
        * @access private
        * @var string
        */
        private $api_key = "ABQIAAAAE42F_jOZV5FbbEaycLh-KhT0302ZmBTdgxHz4oO_crQjpRairhTyeFc_4FIMDa5EWLScOSRqhD50kQ";

        /**
        * Google request User IP
        * @access private
        * @var string
        */
        private $user_ip = randomip;

        /**
        * Constructor sets up {@link $available_languages}
        */
        public function __construct()
        {
                $this->available_languages = parse_ini_file("/home/domeny/domain.cz/web/subdomeny/www/wp-content/plugins/xml-parser/languages.ini");
        }

        /**
        * URL Formater to use on request
        * @access private
        * @param array $lang_pair
        * @param array $string
        * "returns String $url
        */

        private function urlFormat($lang_pair,$string)
        {
                $parameters = array(
                        "v" => $this->api_version,
                        "q" => $string,
                        "langpair"=> implode("|",$lang_pair)
                );

                if(!empty($this->api_key))
                {
                        $parameters["key"] = $this->api_key;
                }

                if( empty($this->user_ip) )
                {
                        if( !empty($_SERVER["REMOTE_ADDR"]) )
                        {
                                $parameters["userip"]   =       $_SERVER["REMOTE_ADDR"];
                        }
                } else
                {
                        $parameters["userip"]   =       $this->user_ip;
                }

                $url  = "";

                foreach($parameters as $k=>$p)
                {
                        $url    .=      $k."=".urlencode($p)."&";
                }
                return $url;
        }

        /**
        * Define the request type
        * @access public
        * @param string $request_type
        * return boolean
        */
        public function setRequestType($request_type = 'http') {
                if (!empty($request_type)) {
                        $this->request_type = $request_type;
                        return true;
                }
                return false;
        }

        /**
        * Define the Google Translate Api Key
        * @access public
        * @param string $api_key
        * return boolean
        */
        public function setApiKey($api_key) {
                if (!empty($api_key)) {
                        $this->api_key = $api_key;
                        return true;
                }
                return false;
        }

        /**
        * Define the User Ip for the query
        * @access public
        * @param string $ip
        * return boolean
        */
        public function setUserIp($ip) {
                if (!empty($ip)) {
                        $this->user_ip = $ip;
                        return true;
                }
                return false;
        }

        /**
        * Query the Google(TM) endpoint
        * @access private
        * @param array $lang_pair
        * @param array $string
        * returns String $response
        */

        public function query($lang_pair,$string)
        {
                $query_url = $this->urlFormat($lang_pair,$string);
                $response = $this->{"request".ucwords($this->request_type)}($query_url);
                return $response;
        }

        /**
        * Query Wrapper for Http Transport
        * @access private
        * @param String $url
        * returns String $response
        */

        private function requestHttp($url)
        {
                return GTranslate::evalResponse(json_decode(file_get_contents($this->url."?".$url)));
        }

        /**
        * Query Wrapper for Curl Transport
        * @access private
        * @param String $url
        * returns String $response
        */

        private function requestCurl($url)
        {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_REFERER, !empty($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
                $body = curl_exec($ch);
                curl_close($ch);
                return GTranslate::evalResponse(json_decode($body));
        }

        /**
        * Response Evaluator, validates the response
        * Throws an exception on error
        * @access private
        * @param String $json_response
        * returns String $response
        */

        private function evalResponse($json_response)
        {
                switch($json_response->responseStatus)
                {
                        case 200:
                                return $json_response->responseData->translatedText;
                                break;
                        //case 400 a 404: přidáno pro případy "invalid result" - nicméně daný článek se přeskočí a nepřeloží
                        case 400:
                                return $json_response->responseData->translatedText;
                                break;
                        case 404:
                                return $json_response->responseData->translatedText;
                                break;
                        default:
                                throw new GTranslateException("Unable to perform Translation:".$json_response->responseDetails);
                        break;
                }
        }


        /**
        * Validates if the language pair is valid
        * Throws an exception on error
        * @access private
        * @param Array $languages
        * returns Array $response Array with formated languages pair
        */

        private function isValidLanguage($languages)
        {
                $language_list  = $this->available_languages;

                $languages              =       array_map( "strtolower", $languages );
                $language_list_v        =       array_map( "strtolower", array_values($language_list) );
                $language_list_k        =       array_map( "strtolower", array_keys($language_list) );
                $valid_languages        =       false;
                if( TRUE == in_array($languages[0],$language_list_v) AND TRUE == in_array($languages[1],$language_list_v) )
                {
                        $valid_languages        =       true;
                }

                if( FALSE === $valid_languages AND TRUE == in_array($languages[0],$language_list_k) AND TRUE == in_array($languages[1],$language_list_k) )
                {
                        $languages      =       array($language_list[strtoupper($languages[0])],$language_list[strtoupper($languages[1])]);
                        $valid_languages        =       true;
                }

                if( FALSE === $valid_languages )
                {
                        throw new GTranslateException("Unsupported languages (".$languages[0].",".$languages[1].")");
                }

                return $languages;
        }

        /**
        * Magic method to understande translation comman
        * Evaluates methods like language_to_language
        * @access public
        * @param String $name
        * @param Array $args
        * returns String $response Translated Text
        */


        public function __call($name,$args)
        {
                $languages_list         =       explode("_to_",strtolower($name));
                $languages = $this->isValidLanguage($languages_list);

                $string         =       $args[0];
                return $this->query($languages,$string);
        }
}

//-------------------------------ořezávátko (počet znaků, zachová slova, a HTML------------------------------------------------------------------------

function orizni($string, $limit, $break=" ") {

 //tato funkce ořízne string na maximální počet znaků (včetně tagů), zachová celá slova a celé HTML tagy (to co je mezi < a >)

 // return with no change if string is shorter than $limit
 if(strlen($string) <= $limit) return $string;
 $string = substr($string, 0, $limit);
 if(false !== ($breakpoint = strrpos($string, $break))) {
  $string = substr($string, 0, $breakpoint);
 }
 
 //v případě neukončeného tagu - oriznuti vseho od posledniho vyskytu <
 if (strrpos($string,"<")>strrpos($string,">")) {
  $string = preg_replace('/(<)[^<]*$/', '', $string);
 }
 
 return $string; }

//-------------------------------funkce překladače------------------------------------------------------------------------------------------------------

function preloz($text) {

                $zpusob_pripojeni = "CURL";

                $max_delka = 5000;

                if (strlen($text)<$max_delka) {
                 
                  $gt = new Gtranslate;
                  if ($zpusob_pripojeni == "HTTP"){
                   $vysledny_text = $gt->slovak_to_czech($text);
                  } elseif ($zpusob_pripojeni == "CURL"){
                 	 $gt->setRequestType('curl');
                   $vysledny_text = $gt->slovak_to_czech($text);
                  }

                } else {
                
                 for ($pocitadlo_orez = 0; strlen($text)>$max_delka; ($pocitadlo_orez = $pocitadlo_orez+1)) {

                  $cast_textu[$pocitadlo_orez] = orizni($text, $max_delka);

                  $text = str_replace($cast_textu[$pocitadlo_orez], "", $text);
                  if (strlen($text)<$max_delka) {
                   $cast_textu[($pocitadlo_orez+1)] = $text;
                  }
                 }
                 
                 $pocet_kusu_textu = count($cast_textu);

                 for ($pocitadlo_prekl = 0; $pocitadlo_prekl<$pocet_kusu_textu; ($pocitadlo_prekl = $pocitadlo_prekl+1)) {

                  $gt = new Gtranslate;
                  if ($zpusob_pripojeni == "HTTP"){
                   $cast_textu[$pocitadlo_prekl] = $gt->slovak_to_czech($cast_textu[$pocitadlo_prekl]);
                  } elseif ($zpusob_pripojeni == "CURL"){
                 	 $gt->setRequestType('curl');
                   $cast_textu[$pocitadlo_prekl] = $gt->slovak_to_czech($cast_textu[$pocitadlo_prekl]);
                  }

                 }

                 for ($pocitadlo_dohr = 0; $pocitadlo_dohr<$pocet_kusu_textu; ($pocitadlo_dohr = $pocitadlo_dohr+1)) {
                  $vysledny_text = $vysledny_text . $cast_textu[$pocitadlo_dohr];
                 }
                }
                
                
                return $vysledny_text;
}
//-------------------------------proměnná na úpravu titlů------------------------------------------------------------------------------------

$velke_pryc = array(
	    "A" => " A",
	    "B" => " B",
	    "C" => " C",
	    "D" => " D",
	    "E" => " E",
	    "F" => " F",
	    "G" => " G",
	    "H" => " H",
	    "I" => " I",
	    "J" => " J",
	    "K" => " K",
	    "L" => " L",
	    "M" => " M",
	    "N" => " N",
	    "O" => " O",
	    "P" => " P",
	    "Q" => " Q",
	    "R" => " R",
	    "S" => " S",
	    "T" => " T",
	    "U" => " U",
	    "V" => " V",
	    "W" => " W",
	    "X" => " X",
	    "Y" => " Y",
	    "Z" => " Z");

//odstranění diakritiky u hrefů
$dia_pryc = array(
	    "á" => "a",
	    "À" => "A",
	    "à" => "a",
	    "Á" => "A",
	    "ä" => "a",
      "Ä" => "A",
      "ą" => "a",
      "Ą" => "A",
	    "ć" => "c",
      "Ć" => "C",
	    "ç" => "c",
      "Ç" => "C",
	    "č" => "c",
	    "Č" => "C",
	    "ď" => "d",
	    "Ď" => "D",
	    "é" => "e",
	    "É" => "E",
	    "è" => "e",
	    "È" => "E",
	    "ě" => "e",
	    "Ě" => "E",
      "ę" => "e",
      "Ę" => "E",
      "Ğ" => "G",
      "ğ" => "g",
      "İ" => "I",
      "ı" => "i",
	    "Í" => "I",
	    "í" => "i",
	    "ĺ" => "l",
	    "Ì" => "I",
	    "ì" => "i",
	    "ľ" => "l",
	    "ł" => "l",
	    "Ł" => "L",
	    "Ĺ" => "L",
	    "Ľ" => "L",
	    "ň" => "n",
	    "ñ" => "n",
	    "Ň" => "N",
	    "Ñ" => "N",
	    "ń" => "n",
	    "Ń" => "N",
	    "ő" => "o",
	    "ó" => "o",
	    "ô" => "o",
	    "Ó" => "O",
	    "Ô" => "O",
	    "ö" => "o",
      "Ö" => "O",
	    "ò" => "o",
	    "Ò" => "O",
	    "ŕ" => "r",
	    "ř" => "r",
	    "Ř" => "R",
	    "Ŕ" => "R",
      "Ş" => "S",
      "ş" => "s",
	    "š" => "s",
	    "Š" => "S",
	    "ś" => "s",
	    "Ś" => "S",
	    "ß" => "s",
	    "ť" => "t",
	    "Ť" => "T",
      "ű" => "u",
	    "Ů" => "U",
	    "Ú" => "U",
      "ü" => "u",
      "Ü" => "U",
	    "ů" => "u",
	    "ú" => "u",
	    "ù" => "u",
	    "Ù" => "U",
	    "ý" => "y",
	    "Ý" => "Y",
	    "ż" => "z",
	    "Ż" => "Z",
	    "ž" => "z",
	    "Ž" => "Z",
	    "ź" => "z",
	    "Ź" => "Z");
	    
//odstranění diakritiky u hrefů
$mezery_pryc = array(
	    " " => "-"
      );

//-------------------------------linky obrázků - připojení k MW api------------------------------------------------------------------------------------

// Fake user agent
ini_set('user_agent','Commons API;');

//-------------------------------benchmark - funkce----------------------------------------------------------------------------------------------------

function getTime() {
    $timer = explode( ' ', microtime() );
    $timer = $timer[1] + $timer[0];
    return $timer;
}

// příklad použití benchmarku
// $start = getTime();
//    měřený skript;
// $end = getTime();
// echo  '<strong>měřený skript zabral:</strong>: '.round($end - $start,4).' sekund<br />';

//-------------------------------includování souborů potřebných pro parsování wikimedia markup-----------------------------------------------------------

/**
 * This is the main cli entry point for MediaWiki.
 *
 * See the README and INSTALL files for basic setup instructions
 * and pointers to the online documentation.
 *
 * ----------
 *
 * Copyright (C) 2008, 2009 Michael Nowak
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
**/

# ShortOptions:
#  -f: Path to the file with wiki-markup or '-' to read wiki-markup from STDIN
#  -l: Path to the file that contains the list of files that contains wiki-markup
#  -m: Memory-Size in MB for PHP
#  -o: Output-Format: XML, HTML, YAML, JSON, TXT
#  -p: Pipe-Mode: file or markup
#  -k: Keep wiki-markup files after parsing
$parserShortOptions = 'f:l:m:o:p:k';
# LongOptions: -
$parserLongOptions = array( 'file', 'list', 'memory-php', 'output-format', 'keep-markup' );
# Get options
$parserOptions = getopt( $parserShortOptions ); #, $parserLongOptions );

if ( isset($parserOptions['m']) ) {
  ini_set( 'memory_limit', $parserOptions['m'].'M' );
} else {
  ini_set( 'memory_limit', '1000M' );
}

if ( isset($parserOptions['o']) ) {
  $outputFormat = strtolower( $parserOptions['o'] );
} else {
  $outputFormat = 'html';
}

if ( isset($parserOptions['t']) ) {
  $wgTemplatePrefix = $parserOptions['t'];
} else {
  $wgTemplatePrefix = 'Template:';
}

ini_set( 'xdebug.max_nesting_level', 250 );

# Initialise common code
require ( dirname(__FILE__) .'/includes/sa/SetupStandAlone.php' );
require ( dirname(__FILE__) .'/includes/WebStart.php' );

wfProfileIn('parser_sa.php');

# Set parser options
wfSetParserOptions();

# Misc
$text   = null;
$action = 'parse';
$format = $outputFormat;
$fileName = null;
$fileList = null;
$extMarkup  = '.markup';
$extArticle = '.article';
$rmMarkup   = (isset($parserOptions['k'])) ? false : true;

//------------------------------------------------------------------------------------------------------------------------------------------------

# Global function to set parser options
function wfSetParserOptions() {
  global $wgParserOptions, $wgParser;
  $wgParserOptions = new ParserOptions(null);
  $wgParserOptions->setEditSection(false);
  $wgParser->Options($wgParserOptions);
  return $wgParserOptions;
}

# Global function for parsing text with ApiMain
function wfParseText($text, $action='parse', $format='xml') {
	# Initialise faux request
	$cliRequest = new FauxRequest( array( 'action' => &$action, 'text' => &$text, 'format' => &$format ) );

	# Initialise api and execute
	$processor = new ApiMain($cliRequest);
	$processor->execute();

	# generate result and print the result
	$printer = $processor->createPrinterByName($format);
	$result = $processor->getResult();
	if ($printer->getNeedsRawData()) {
		$result->setRawMode();
	}
    $result->cleanUpUTF8();
    #$printer->profileIn();
	$printer->initPrinter(false);
	$printer->execute();
	$printer->closePrinter();
    #$printer->profileOut();
    return true;
}


### Wikipedia Offline Client - Stuff

function &wfOutputWrapperWOC($articleTitle, $articleText) {
  global $wgLanguageCode, $wgDocType, $wgDTD, $wgXhtmlDefaultNamespace;
  $articleOutput = '<!DOCTYPE html PUBLIC "'. $wgDocType .'" "'. $wgDTD .'">'.
    '<html xmlns="'. $wgXhtmlDefaultNamespace .'" xml:lang="'. $wgLanguageCode .'" lang="'. $wgLanguageCode .'">'.
    '<head><title>'. $articleTitle .'</title></head><body>'. $articleText .'</body></html>';
  return $articleOutput;
}

# Global function for 'Wikipedia Offline Client'-specific parsing
function &wfParseTextWOC($text) {
  global $wgParser, $wgParserOptions;
  $nlidx = strpos($text, "\n");
  // tady se dělá title
  // $articleTitle = trim(substr($text, 0, $nlidx));
  // $articleTitle = "Bonsai";
  global $articleTitle;

  $articleMarkup = substr($text, $nlidx + 1);
  $title = Title::newFromText($articleTitle);
  $output = $wgParser->parse($articleMarkup, $title, $wgParserOptions, true, true, null);
  $articleText = $output->getText();
  return array( &$articleTitle, &$articleText );
}

# Global function for 'WOC'-specific parsing
function &wfParseTextAndWrapWOC($text) {
  $result = wfParseTextWOC($text);
  $articleTitle = $result[0];
  $articleText = $result[1];
  $articleOutput = wfOutputWrapperWOC($articleTitle, $articleText);
  return $articleOutput;
}

function &wfParseTextAndSkin($text) {
  global $wgParser;
  $wgParser;
}

# Global helper function for 'WOC'-specific parsing
/** function for reading a file from end **/
/**
  * before you call this function first time on a handle
  * the file pointer have to be set at the end of the file '-2'
  * e.g. 'fseek($fileHandle, -2, SEEK_END);'
 **/
function fgets_reverse ($handle)
{
  $s_a = array();
  while("" != ($c = fread($handle, 1)))
  {
    if (ftell($handle) == 0)
    {
      fseek($handle, 0, SEEK_SET);
      break; // we are at the start of the file
    }
    else
    {
      fseek($handle, -2, SEEK_CUR);
    }
    if ($c != "\n" && $c != "")
    {
      array_push($s_a, $c);
    }
    else
    {
      break; // we are at the end of the line
    }
  }
  return implode("", array_reverse($s_a)); // create a string from this array in reversed order
}

//-------------------------------------------otevření databáze s posledním title---------------------------------------------------------------------
/*--otevření databáze, načtení titlu posledního vydaného článku, je v $posledni_title --*/

// Připojení k databázi.
$db_spojeni = mysqli_connect
  ('localhost', '[name]', '[password]', 'domain_wikipedia_ptitle', 3306);

// Otestování, zda se připojení podařilo.
if ($db_spojeni){
  //echo '<b>Připojení k databázi domain_wikipedia_ptitle se podařilo</b><br />';
  }
else
{
  echo '<b>Připojení k databázi domain_wikipedia_ptitle se nepodařilo, sorry.</b><br />';
  echo '<br />';
  echo 'Popis chyby: ', mysqli_connect_error();
  exit();
}

// Zaslání SQL příkazu do databáze.
$objekt_vysledku = mysqli_query($db_spojeni, 'SELECT * FROM poslednivydany');

if (!$objekt_vysledku)
{
  echo 'Poslání SQL příkazu se nepodařilo, sorry';
  echo '<br />';
  echo 'Popis chyby: ', mysqli_error($db_spojeni);
  exit();
}

// Zobrazení titlu (jde o title posledního vydaného článku).
$radek = mysqli_fetch_array($objekt_vysledku);
$posledni_title = $radek['title'];
//>>>echo '<b>Title posledního vydaného článku: ', $posledni_title, '</b><br />';
//echo '<br />';

//nastavení proměnné
$jeste_nebylo_vydano = false;
$navazujeme = true;

//pokud se začíná a databáze je prázdná
if (is_null($posledni_title)){
 $pocitadlo_vydavani = 1;
 $jeste_nebylo_vydano = true;
 $navazujeme = false;
 $ubehlo_minule = 0;
 $clanku_minule = 0;
}

//připojení k databázi s posledním časem a počtem článků
  // Připojení k databázi.
  $db_spojeni_cas = mysqli_connect
    ('localhost', '[domain]', '[password]', 'domain_wikipedia_cas', 3306);

  // Otestování, zda se připojení podařilo.
  if ($db_spojeni_cas){
    //echo '<b>Připojení k databázi domain_wikipedia_cas se podařilo</b><br />';
  }
  else
  {
    echo '<b>Připojení k databázi domain_wikipedia_cas se nepodařilo, sorry.</b><br />';
    echo '<br />';
    echo 'Popis chyby: ', mysqli_connect_error();
    exit();
  }
  
if ($navazujeme == true){
  // Zaslání SQL příkazu do databáze.
  $objekt_vysledku = mysqli_query($db_spojeni_cas, 'SELECT * FROM posledni');

  if (!$objekt_vysledku)
  {
    echo 'Poslání SQL příkazu se nepodařilo, sorry';
    echo '<br />';
    echo 'Popis chyby: ', mysqli_error($db_spojeni_cas);
    exit();
  }

  // Pokud se navazuje, načtení toho kolik času naposled uběhlo a kolik uběhlo článků
  $radek = mysqli_fetch_array($objekt_vysledku);
  $ubehlo_minule = $radek['ubehly_cas'];
  $clanku_minule = $radek['ubehly_pocet_cl'];
  
  //echo "<b>Při minulém běhu uteklo: " . $ubehlo_minule . " sekund a importovalo se " . $clanku_minule . " článků</b></br>";
}


//#############################################  zpracovávání XML souboru  ###########################################################################
$cesta_ke_xml = "compress.bzip2://http://www.domain.cz/wp-content/plugins/xml-parser/skwiki-20110618-pages-articles.xml.bz2";
$xml = new XMLReader();
$xml->open($cesta_ke_xml);
$xml->setParserProperty(2,true);

$pocitadlo = 0;

while ($xml->read()) {

 switch ($xml->name) {
  case "title":
   $xml->read();
   $conf["title"] = $xml->value;

   $pocitadlo = $pocitadlo + 1;
   
   $xml->read();
   break;
  case "text":
   $xml->read();
   // v $conf["text"] je vysledny text (wikimedia markup)
   $conf["text"] = $xml->value;

   if ($pocitadlo > 0) {

    // v této části se proměnná $text parsuje z MW markup na normální HTML------------------------------------------------------------------------------
    //$start[0] = getTime();

    #$arg1_prefix = substr($argv[1], 0, 1);

    //vstupy
    $text = $conf["text"];
    $articleTitle = $conf["title"];
    
    //pro licenční informaci dole
    $titulek_licence = $conf["title"];
    
    //zpracujeme a vydáme článek?-------------------------------------------------


    //titulek: zatím nepřekládáme, necháme anglicky
    $titulek_clanku = $articleTitle;
    $pocitadlo_titulku = $pocitadlo_titulku + 1;

    /* podmínka zkoumá, jestli jsme došli k poslednímu publikovanému článku */
    if ($jeste_nebylo_vydano == false) {
     if ($titulek_clanku == $posledni_title){
      //>>>>echo $pocitadlo_titulku . ". <b>Tenhle post (" . $titulek_clanku . ") už byl vydán, proměnnou \$jeste_nebylo_vydano nastavíme na true a od teď +1 budeme vydávat</b><br />";
      $jeste_nebylo_vydano = true;
      $pocitadlo_vydavani = 0;
     } else {
       if ($pocitadlo_vydavani<2) {
        //>>>>echo $pocitadlo_titulku . ". <b>Zatím jsme nedošli k poslednímu vydanému postu (title, na kterém jsme je:" . $titulek_clanku . ")</b><br />";
       } else if ($pocitadlo_vydavani>=2) {
        //>>>>echo $pocitadlo_titulku . ". <b>Vydáváme:" . $titulek_clanku . "</b><br />";
       }
      //$jeste_nebylo_vydano = false;
     }
    }
    
    if ($jeste_nebylo_vydano == true) {
     $pocitadlo_vydavani = $pocitadlo_vydavani+1;
    }

   //přinucení echo, aby se hned vypsali do prohlížeče (pozor, moc to nefunguje)
   //>>>>if (je_nasobek($pocitadlo_titulku, 50) == true){
   //>>>> buffer_flush();
   //>>>>}

   //zpracujeme a vydáme článek? (pokud je třeba při navázání 1 přeskočit, stačí asi 2 změnit na 3)---------------------------------------------
   //trik, aby se to nedostalo do smyčky (lepší by byla podmínka hlídající obsah chybové hlášky - bylo to memory exhausted)
   $hranice = rand(0, 3);
   $hranice = 0;
   if ($jeste_nebylo_vydano == true AND $pocitadlo_vydavani>=$hranice) {

    //titulek: vyrobit mezeru, přeložit,
    $titulek_clanku = ltrim(strtr($articleTitle, $velke_pryc));
    $titulek_clanku = preloz($titulek_clanku);
    
    //úprava eskapovaných \" v překladu
    $titulek_clanku = str_replace('\"', '"', $titulek_clanku);

    //úprava před sparsováním - odstranit ==Further reading== do konce

    $text = preg_replace("/==Iné projekty==(.*)$/s", "", $text);
    //tento řádek dělal problémy (chtěl downloadovat source)
    $text = preg_replace("/==Referencie==(.*)$/s", "", $text);
    
    //zbavíme se ještě něčeho v patičce
    $text = preg_replace("/==Externé odkazy==(.*)$/s", "", $text);

    //úprava: pokud u redirectu chybí odkaz na šablonu, přidat
    $pozice_redirectu = stripos($text, "REDIRECT");
    if($pozice_redirectu === false) {
     // jehla NEnalezena v kupce
    } else {
     if (strlen($text)<500) {
      // jehla nalezena v kupce
      $text = str_replace("\n", "", $text);
      $text = str_replace("\r", "", $text);
     }
    }
    
    //parser odříznul první odstavec, tohle přidává odřádkování, aby oříznul jen to
    $text = "\n" . $text;

    //echo "<font style=\"color: #ff0000\">";
    //echo $text;
    //echo "</font><br />";

    if ( $outputFormat == 'woc' ) {
     $result = wfParseTextAndWrapWOC(&$text); # explicit echo needed
    }
    elseif ( $outputFormat == 'html' ) {
     $result = wfParseTextWOC(&$text);
     //echo $result[1]; # explicit echo needed
    }
    else {
     wfParseText(&$text, $action, $outputFormat); # echo included
    }

    //oříznutí mezer z obou stran (způsobovalo prázdný text a zhavarování překladu)
    //přesunutí až za parser
    $result[1] = trim($result[1]);
    
    // konec parsování na HTML, následují úpravy--------------------------------------------------------------------------------------

    //pokus o výmaz infoboxů
    $result[1] = preg_replace("/\{Infobox(.*)\}\}/s", "", $result[1]);
    $result[1] = preg_replace("/\|(.*)\|/s", "", $result[1]);
    //problémový řádek, někdy vymazal celý text
    //$result[1] = preg_replace ("/^(.*)=\}\}/s", "", $result[1]);

    //výmaz portály a wikidictionary
    $result[1] = str_replace("<td><div class=\"center\"><div class=\"floatnone\"><img src=\"http://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Wiktionary-logo-en.svg/200px-Wiktionary-logo-en.svg.png\" alt=\"Wiktionary-logo-en.svg\" style=\"max-width: 182px;\"></div></div></td><td>look up in <i><b>Wiktionary</b></i>\n</td>", "", $result[1]);
    $result[1] = str_replace("<td><div class=\"center\"><div class=\"floatnone\"><img src=\"http://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Commons-logo.svg/200px-Commons-logo.svg.png\" alt=\"Commons-logo.svg\" style=\"max-width: 182px;\"></div></div></td><td>Media and images from <i><b>commons:Category:Academy-Awards\" class=\"extiw\" title=\"commons:Category:Academy Awards\"&gt;Commons</b></i>nn</td>", "", $result[1]);

    //výmaz jazyky
    $result[1] = preg_replace("/<p><a href=\"\/(Af|Ar|Az|Bn|Bs|Bg|Ca|Cs|Co|Cy|Da|De|Et|El|Es|Eo|Fa|Fr|Fy|Ga|Gl|Ko|Hy|Hi|Hr|Io|Id|Is|It|He|Kn|Ka|La|Lv|Lt|Hu|Mk|Ml|Mr|Ms|Nl):.*$/", "", $result[1]);

    //úprava šířky boxů s obrázky
    $result[1] = preg_replace ("/<div class=\"thumbinner\"(.*)px;\">/Umis", "<div class=\"thumbinner\" style=\"width: 182px;\">", $result[1]);

    //vymazání linků "Šablóna"
    $result[1] = preg_replace('/<a (?=[^>]*(Šablóna)).*<\/a>/Umis', '', $result[1]);

    //vymazání odkazů na poznámky pod čarou (footnotes)
    $result[1] = preg_replace('/<sup (?=[^>]*).*<\/sup>/Umis', '', $result[1]);
    $result[1] = str_replace("<p>\n</p>\n<h2> <span class=\"mw-headline\" id=\"Footnotes\">Footnotes</span></h2>", "", $result[1]);

    //vymazání linků "Footnotes" z obsahu
    $result[1] = preg_replace('/<li .*Footnotes.*<\/li>/', '', $result[1]);

    //překopání linků na články na www.domain.sk/nazev_clanku
    $result[1] = preg_replace("/wp-content\/plugins\/xml-parser\/mediawiki-1.16.0\/index\.php\?title=([^&]*)&amp;action=edit/", "$1/", $result[1]);
    $result[1] = preg_replace("/wp-content\/plugins\/xml-parser\/mediawiki-1.16.0\/index\.php\/([^\"]*)/", "$1/", $result[1]); #woc
    
    //smazání /&amp;redlink=1
    $result[1] = str_replace("/&amp;redlink=1", "", $result[1]);
    
    //smazání (stránka neexistuje) v lincích
    $result[1] = str_replace(" (stránka neexistuje)", "", $result[1]);

    //odstranění odkazů na Wiktionary
    $result[1] = preg_replace('#<a href="http://en.wiktionary.*?>([^>]*)</a>#i', '$1', $result[1]);

    //odstranění mezery na konci článku <p>\n</p><p><br />\n</p>$
    $result[1] = preg_replace('/<p>\n<\/p><p><br \/>\n<\/p>/', '', $result[1]);
    $result[1] = preg_replace('/<p><br \/>\n<\/p>/', '', $result[1]);

    //vymazání chybové hlášky
    $result[1] = str_replace("<br /><strong class=\"error\">Chyba citácie Značky <code>&lt;ref&gt;</code> sú prítomné, ale nebola nájdená žiadna značka <code>&lt;references/&gt;</code></strong>", "", $result[1]);
 
    //oprava cesty ke vzorcům
    $result[1] = str_replace("images/math", "http://www.domain.cz/wp-content/plugins/xml-parser/mediawiki-1.16.0/images/math", $result[1]);

    //předělání názvů obrázků na skutečné linky na ně---------------------------------------------------------------------------------------

    //vytahání názvů obrázků z hrefů
    //$start[1] = getTime();
    
    $input = $result[1];
    if(preg_match_all('/<a (?=[^>]*(jpg|jpeg|gif|png|bmp|svg|JPG|JPEG|GIF|PNG|BMP|SVG)).*<\/a>/Umis', $input, $matches)) {
    }
    
    $seznam_obrazku = $matches[0];
    
    $seznam_obrazku_puvodni = $matches[0];
    
    //počet obrázků
    $pocet_obrazku = count($seznam_obrazku);
    
    //jsou v postu obrázky?
    if ($pocet_obrazku >= 1) {
     $jsou_tam_obrazky = true;
    } else {
     $jsou_tam_obrazky = false;
    }
    
    //kód z https://svn.toolserver.org/svnroot/magnus/commonsapi.php, funguje taky na toolserver.org/~magnus/commonsapi.php
    for ($pocitadlo_obrazky=0; $pocitadlo_obrazky<$pocet_obrazku; ($pocitadlo_obrazky = $pocitadlo_obrazky + 1)) {

     //oprava:
     $seznam_obrazku[$pocitadlo_obrazky] = preg_replace ("/^.*File=/", "", $seznam_obrazku[$pocitadlo_obrazky]);
     $seznam_obrazku[$pocitadlo_obrazky] = preg_replace("/\" class.*$/", "", $seznam_obrazku[$pocitadlo_obrazky]);
     

     $seznam_obrazku[$pocitadlo_obrazky] = preg_replace("/\r/", "", $seznam_obrazku[$pocitadlo_obrazky]);
     $seznam_obrazku[$pocitadlo_obrazky] = preg_replace("/\n/", "", $seznam_obrazku[$pocitadlo_obrazky]);

     $ii_url = "http://commons.wikimedia.org/w/api.php?format=php&action=query&prop=imageinfo&iilimit=500&iiprop=timestamp|user|url|size|sha1|metadata&titles=Image:" . rawurlencode($seznam_obrazku[$pocitadlo_obrazky]);

     $data = unserialize ( file_get_contents ( $ii_url ) ) ;
     $data = array_shift ( $data['query']['pages'] ) ;
     $data = $data['imageinfo'] ;
     $seznam_odkazu[$pocitadlo_obrazky] = $data[0]['url'] ;

     // kontrola je.li svg, v tom případě vzít 200px png
     $pozice_substringu = strpos($seznam_odkazu[$pocitadlo_obrazky], ".svg");
     if($pozice_substringu === false) {
     }
     else {
      $seznam_odkazu[$pocitadlo_obrazky] = str_replace("commons", "commons/thumb", $seznam_odkazu[$pocitadlo_obrazky]);
      $seznam_odkazu[$pocitadlo_obrazky] = $seznam_odkazu[$pocitadlo_obrazky] . "/200px-" . $seznam_obrazku[$pocitadlo_obrazky] . ".png";
     }

     if ($seznam_odkazu[$pocitadlo_obrazky] == NULL) {
      $seznam_odkazu[$pocitadlo_obrazky] = "http://www.domain.cz/wp-content/plugins/xml-parser/presunuto.jpg";
     }
    }
    

    //nahrazení názvů obrázků na skutečné linky na ně přímo v HTML
    for ($pocitadlo_nahrazovani=0; $pocitadlo_nahrazovani<$pocet_obrazku; ($pocitadlo_nahrazovani = $pocitadlo_nahrazovani + 1)) {

     $result[1] = str_replace($seznam_obrazku_puvodni[$pocitadlo_nahrazovani], "<img src=\"http://www.domain.cz/wp-content/themes/gothamnews/thumb.php?src=" . $seznam_odkazu[$pocitadlo_nahrazovani] . "&w=182&zc=1&q=90\" alt=\"" . $seznam_obrazku[$pocitadlo_nahrazovani] . "\" style=\"max-width: 182px\">", $result[1]);

     //uložení cesty k prvnímu obrázku (vyrobí se z toho thumb)
     if ($pocitadlo_nahrazovani == 0) {
      //tady je kód, který v případě že je první obrázek náhradní, dá místo něj obrázek náhodný
      if ($seznam_odkazu[$pocitadlo_nahrazovani] == "http://www.domain.cz/wp-content/plugins/xml-parser/presunuto.jpg") {
       //pole, ktere bude obsahovat seznam nazvu obrazku
       $soubory_s_obrazky1 = Array();

       //otevreme slozku
       $slozka_s_obr1 = dir('obrazky_encyklo');

       //projdeme vsechny soubory a vybereme jpg obrazky
       while ($soubor_s_obrazky1 = $slozka_s_obr1->read())
        if (substr_count($soubor_s_obrazky1, ".jpg") > 0) $soubory_s_obrazky1[] = $soubor_s_obrazky1;

       //vypiseme obrazek
       $cesta_k_prvnimu_obrazku = "http://www.domain.cz/wp-content/plugins/xml-parser/mediawiki-1.16.0/obrazky_encyklo/" . $soubory_s_obrazky1[rand(0, count($soubory_s_obrazky1)-1)];
      } else {
       $cesta_k_prvnimu_obrazku = $seznam_odkazu[$pocitadlo_nahrazovani];
      }
     }

    }

           
    //smazání sekce Obsah (generuje jí parser z markupu na HTML)
    $result[1] = preg_replace ("/<li class=\"toclevel(.*)<\/li>/siU", "", $result[1]);
    $result[1] = preg_replace ("/<table id=\"toc\"(.*)<h2> <span class=\"mw-headline/siU", "<h2> <span class=\"mw-headline", $result[1]);

    //odmazání všech hrefů (ale ponechání slov mezi nimi)
    
    $result[1] = preg_replace ("/<a href=\"([^<]*)\">([^<]*)<\/a>/", "$2", $result[1]);
    
    //odmazání některých částí na konci
    
    $result[1] = preg_replace("/<h2> <span class=\"mw-headline\" id=\"Referencie\"> Referencie <\/span><\/h2>(.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/<h2> <span class=\"mw-headline\" id=\"In.C3.A9_projekty\"> Iné projekty <\/span><\/h2>(.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/<h2> <span class=\"mw-headline\" id=\"Extern.C3.A9_odkazy\"> Externé odkazy <\/span><\/h2>(.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/<h2> <span class=\"mw-headline\" id=\"Zdroje\"> Zdroje <\/span><\/h2>(.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/<h2> <span class=\"mw-headline\" id=\"Gal.C3.A9ria\"> Galéria <\/span><\/h2>(.*)$/s", "", $result[1]);
   
    //překlad textu do CZ-------------------------------------------------------------------------------------------------------------------------------

    //překlad titulků
    $result[1] = preloz($result[1]);
    
    //ještě jeden pokus o vyházení jazyků na konci
    $result[1] = preg_replace("/ aa: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ab: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ace: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ af: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ak: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ als: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ am: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ an: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ang: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ar: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ arc: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ arz: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ as: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ast: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ av: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ay: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ az: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ba: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bar: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bat-smg: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bcl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ be: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ be-x-old: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bg: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bh: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bjn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bm: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bpy: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ br: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bs: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bug: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ bxr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ca: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ cbk-zam: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ cdo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ce: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ceb: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ckb: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ co: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ cr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ crh: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ cs: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ csb: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ cu: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ cv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ cy: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ da: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ de: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ diq: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ dsb: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ dv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ dz: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ee: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ el: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ eml: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ en: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ eo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ es: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ et: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ eu: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ext: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ fa: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ff: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ fi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ fiu-vro: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ fj: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ fo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ fr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ frp: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ frr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ fur: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ fy: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ga: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ gag: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ gan: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ gd: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ gl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ glk: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ gn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ got: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ gu: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ gv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ha: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ hak: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ haw: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ he: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ hi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ hif: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ho: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ hr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ hsb: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ht: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ hu: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ hy: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ hz: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ch: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ cho: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ chr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ chy: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ia: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ id: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ie: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ig: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ii: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ik: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ilo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ io: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ is: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ it: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ iu: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ja: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ jbo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ jv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ka: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kaa: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kab: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kg: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ki: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kj: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kk: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ km: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ko: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ koi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ krc: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ks: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ksh: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ku: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ kw: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ky: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ la: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lad: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lb: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lbe: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lg: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ li: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lij: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lmo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ln: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lt: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ lv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ map-bms: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mdf: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mg: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mh: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mhr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mk: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ml: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mrj: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ms: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mt: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mus: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mwl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ my: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ myv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ mzn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ na: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nah: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nap: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nds: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nds-nl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ne: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ new: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ng: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ no: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nov: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nrm: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ nv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ny: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ oc: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ om: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ or: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ os: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pa: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pag: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pam: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pap: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pcd: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pdc: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pfl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pih: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pms: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pnb: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pnt: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ps: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ pt: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ qu: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ rm: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ rmy: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ rn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ro: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ roa-rup: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ roa-tara: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ru: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ rue: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ rw: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sa: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sah: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sc: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ scn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sco: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sd: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ se: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sg: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sh: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ si: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ simple: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sk: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sm: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ so: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sq: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ srn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ss: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ st: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ stq: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ su: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sv: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ sw: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ szl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ta: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ te: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tet: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tg: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ th: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ti: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tk: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tl: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tn: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ to: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tpi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tr: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ts: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tt: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tum: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ tw: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ty: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ udm: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ug: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ uk: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ur: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ uz: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ ve: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ vec: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ vi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ vls: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ vo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ wa: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ war: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ wo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ wuu: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ xal: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ xh: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ yi: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ yo: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ za: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ zea: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ zh: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ zh-classical: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ zh-min-nan: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ zh-yue: (.*)$/s", "", $result[1]);
    $result[1] = preg_replace("/ zu: (.*)$/s", "", $result[1]);

    //úprava eskapovaných \" v překladu
    $result[1] = str_replace('\"', '"', $result[1]);
   
    $result[0] = $titulek_clanku;
    
    //překlad linků v textu do SK-------------------------------------------------------------------------------------------------------------------------------

    //vytahání obsahu linků z hrefů (bereme všechny hrefy, kde mezi tagy není slovo span)
    if(preg_match_all("/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(((?!span).)*)<\/a>/siU", $result[1], $odpovidajici)) {
    }

    $seznam_linku = $odpovidajici[0];

    //počet linků
    $pocet_linku = count($seznam_linku);
    
    for ($pocitadlo_nahrazovani_l=0; $pocitadlo_nahrazovani_l<$pocet_linku; ($pocitadlo_nahrazovani_l = $pocitadlo_nahrazovani_l + 1)) {

     //obsah hrefů
     $seznam_linku_cast_en[$pocitadlo_nahrazovani_l] = str_replace ("<a href=\"/", "", $seznam_linku[$pocitadlo_nahrazovani_l]);
     $seznam_linku_cast_en[$pocitadlo_nahrazovani_l] = preg_replace ("/\" class.*$/", "", $seznam_linku_cast_en[$pocitadlo_nahrazovani_l]);
     //obsah titlů
     $seznam_linku_cast_cs[$pocitadlo_nahrazovani_l] = preg_replace ("/^.*title=\"/", "", $seznam_linku[$pocitadlo_nahrazovani_l]);
     $seznam_linku_cast_cs[$pocitadlo_nahrazovani_l] = preg_replace ("/\">.*$/", "", $seznam_linku_cast_cs[$pocitadlo_nahrazovani_l]);
     //odstranění diakritiky
     $seznam_linku_cast_cs[$pocitadlo_nahrazovani_l] = strtr($seznam_linku_cast_cs[$pocitadlo_nahrazovani_l], $dia_pryc);
     //místo mezer pomlčky
     $seznam_linku_cast_cs[$pocitadlo_nahrazovani_l] = strtr($seznam_linku_cast_cs[$pocitadlo_nahrazovani_l], $mezery_pryc);
     //překlad linku v array
     $seznam_linku_prelozeno[$pocitadlo_nahrazovani_l] = str_replace ($seznam_linku_cast_en[$pocitadlo_nahrazovani_l], $seznam_linku_cast_cs[$pocitadlo_nahrazovani_l], $seznam_linku[$pocitadlo_nahrazovani_l]);
     //nahrazení linků v textu
     $result[1] = str_replace($seznam_linku[$pocitadlo_nahrazovani_l], $seznam_linku_prelozeno[$pocitadlo_nahrazovani_l], $result[1]);
    }
    //další menší úpravy-------------
    //ostranění závorek po výslovnosti
    $result[1] = str_replace("()", "", $result[1]);
    
    //odstranění gallery boxů
    $result[1] = preg_replace("/(<table class=\"gallery)(.*)(<\/table>)/s", "", $result[1]);
  
    //vydání textu ve Wordpressu-------------------------------------------------------------------------------------------------------------------------
   
    //jestliže je to redirect
    $pozice_redirect = stripos($result[1],"Redirect");
    if($pozice_redirect === false) {
     $je_to_redirect = false;
     //přidání informace o licenci
     //$result[1] = $result[1] . "<span class=\"copyinfo\">Zdroj: <i>http://sk.wikipedia.org/wiki/" . $titulek_licence . "</i>. Licence: <a href=\"http://creativecommons.org/licenses/by-sa/3.0/\">Creative Commons Attribution/Share Alike 3.0</a>.</span>";
     $result[1] = $result[1] . "<span class=\"copyinfo\">Zdroj: <i>Wikipedia.org</i>. Licence: <a href=\"http://creativecommons.org/licenses/by-sa/3.0/\">Creative Commons Attribution/Share Alike 3.0</a>.</span>";
    }
    else {
     $je_to_redirect = true;
     $result[1] = str_ireplace ("Redirect", "Viz", $result[1]);
     $presmeruj_sem = "/" . $seznam_linku_cast_cs[0];
     // jehla nalezena v kupce
    }

    if ($je_to_redirect == true){
     $post = array(
      "title"         => $result[0],
      "description"   => $result[1],
      "categories"    => array('Témata'),
      "custom_fields" => array(
          array( "key" => "redirect", "value" => $presmeruj_sem )
      )
     );
    } else {

     //titulek adult?
     $je_to_adult = false;
     $je_to_na_vyhozeni = false;
     
     $zakazana_slova = array('Wikipedie:','Wiki:','hlavní stránka','Seznam','Nápověda:','Šablona:');
     $zakazana_slova = join("|", $zakazana_slova);
     $shoda = array();
     if ( preg_match('/' . $zakazana_slova . '/i', $result[0], $shoda) ){
         $je_to_na_vyhozeni = true;
     }
     
     $zakazana_slova3 = array('sex','erotik','porno','homosex','erotick',);
     $zakazana_slova3 = join("|", $zakazana_slova3);
     $shoda3 = array();
     if ( preg_match('/' . $zakazana_slova3 . '/i', $result[0], $shoda3) ){
         $je_to_adult = true;
     }
     
     //text adult?
     $zakazana_slova2 = array('Rozlišovací stránka');
     $zakazana_slova2 = join("|", $zakazana_slova2);
     $shoda2 = array();
     if ( preg_match('/' . $zakazana_slova2 . '/i', $result[1], $shoda2) ){
         $je_to_na_vyhozeni = true;
     }
     
     $zakazana_slova4 = array('erotik','porno','homosex','erotick');
     $zakazana_slova4 = join("|", $zakazana_slova4);
     $shoda4 = array();
     if ( preg_match('/' . $zakazana_slova4 . '/i', $result[1], $shoda4) ){
         $je_to_adult = true;
     }
     
     //obsahuje text hlášku Rozlišovacia stránka? (Rozlišovací stránka)
     $pozice_rozlisovaci = stripos($result[1], "Rozlišovací stránka");

     //je text kratší než excerpt = 50 slov?
     $celkovy_pocet_slov = count(explode(" ", $result[1]));
     if ($celkovy_pocet_slov < 80){
      $moc_kratky_text = true;
     } else {
      $moc_kratky_text = false;
     }

     /* rozříznutí contentu description na excerpt a obsah podle počtu slov */
     $perex = orizni($result[1], 250);
     $pocet_slov_v_perexu = count(explode(" ", $perex));
     if ($pocet_slov_v_perexu < 40){
      $perex = orizni($result[1], 500);
      $pocet_slov_v_perexu = count(explode(" ", $perex));
     }
     if ($pocet_slov_v_perexu < 40){
      $perex = orizni($result[1], 750);
      $pocet_slov_v_perexu = count(explode(" ", $perex));
     }
     if ($pocet_slov_v_perexu < 40){
      $perex = orizni($result[1], 1000);
      $pocet_slov_v_perexu = count(explode(" ", $perex));
     }
     if ($pocet_slov_v_perexu < 40){
      $perex = orizni($result[1], 1250);
      $pocet_slov_v_perexu = count(explode(" ", $perex));
     }
     if ($pocet_slov_v_perexu < 40){
      $perex = orizni($result[1], 1500);
      $pocet_slov_v_perexu = count(explode(" ", $perex));
     }
     if ($pocet_slov_v_perexu < 40){
      $perex = orizni($result[1], 1750);
      $pocet_slov_v_perexu = count(explode(" ", $perex));
     }
     $result[1] = str_replace($perex, "", $result[1]);
     $perex .= '&hellip;';
     $perex = str_replace("\r","",$perex);
     $perex = str_replace("\n"," ",$perex);
     $result[1] = '&hellip;'.$result[1];
     
     /* odstranění všeho z exerptu od začátku po }} */
     $perex = preg_replace("/^(.*)}}/s", "", $perex);
     
     /* odstranění popisků obrázků excerptu - např.: <div class="thumbcaption"> Rozšíření křesťanství na světě </div> */
     $perex = preg_replace("/<div class=\"thumbcaption\">([^>]*)<\/div>/s", "", $perex);
     
     $perex = str_replace("</h2>",":",$perex);
     $perex = str_replace("</li>",",",$perex);

     /* odstranění všech HTML tagů z excerptu */
     $perex = strip_html_tags($perex);
     $perex = str_replace("\r","",$perex);
     $perex = str_replace("\n"," ",$perex);

     /* výroba thumbu pro posty s obrázky */
     if ($jsou_tam_obrazky == true){
     
      $post = array(
       "title"         => $result[0],
       "description"   => $result[1],
       "mt_excerpt"    => $perex,
       "categories"    => array('Témata'),
             "custom_fields" => array(
                 array( "key" => "image", "value" => $cesta_k_prvnimu_obrazku )
             )
      );

     } else {

      //pole, ktere bude obsahovat seznam nazvu obrazku
      $soubory_s_obrazky = Array();

      //otevreme slozku
      $slozka_s_obr = dir('obrazky_encyklo');

      //projdeme vsechny soubory a vybereme jpg obrazky
      while ($soubor_s_obrazky = $slozka_s_obr->read())
       if (substr_count($soubor_s_obrazky, ".jpg") > 0) $soubory_s_obrazky[] = $soubor_s_obrazky;

      //vypiseme obrazek
      $cesta_k_nah_obrazku = "http://www.domain.cz/wp-content/plugins/xml-parser/mediawiki-1.16.0/obrazky_encyklo/" . $soubory_s_obrazky[rand(0, count($soubory_s_obrazky)-1)];

      $post = array(
       "title"         => $result[0],
       "description"   => $result[1],
       "mt_excerpt"    => $perex,
       "categories"    => array('Témata'),
             "custom_fields" => array(
                 array( "key" => "image", "value" => $cesta_k_nah_obrazku )
             )
      );

     }

   }
   
     //1) podmínka - pokud je v titulku rok
     if (is_numeric($result[0])) {
      //2) v tom případě přidat před to "rok-" a udělat z toho slug (přidat ho do arraye)
      $post["wp_slug"] = "rok-" . $result[0];
     }
     
     //jestliže je to adult, hodit do speciální kategorie
     if ($je_to_adult == true) {
      $post["categories"] = array('Sex a zdraví');
     }

     //vydat jen pokud to není redirect / a pokud to není rozlišovací stránka
     if ($je_to_redirect != true AND $pozice_rozlisovaci === false AND $moc_kratky_text == false AND $je_to_na_vyhozeni != true){
     
      $client->query('metaWeblog.newPost', '', 'WP', 'wikina123wikina', $post, true);
      $end[6] = getTime();

      //časování nových postů

      $kolik_je_hodin = date('H:i');

      if(($kolik_je_hodin >= "06:00") AND ($kolik_je_hodin <= "7:00")) {
       $po_kolika_minutach = 30;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "07:00") AND ($kolik_je_hodin <= "8:00")) {
       $po_kolika_minutach = 20;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "08:00") AND ($kolik_je_hodin <= "9:00")) {
       $po_kolika_minutach = 15;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "9:00") AND ($kolik_je_hodin <= "10:00")) {
       $po_kolika_minutach = 10;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "10:00") AND ($kolik_je_hodin <= "11:00")) {
       $po_kolika_minutach = 7;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "11:00") AND ($kolik_je_hodin <= "12:00")) {
       $po_kolika_minutach = 15;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "12:00") AND ($kolik_je_hodin <= "13:00")) {
       $po_kolika_minutach = 20;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "13:00") AND ($kolik_je_hodin <= "14:00")) {
       $po_kolika_minutach = 15;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "14:00") AND ($kolik_je_hodin <= "15:00")) {
       $po_kolika_minutach = 15;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "15:00") AND ($kolik_je_hodin <= "16:00")) {
       $po_kolika_minutach = 10;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "16:00") AND ($kolik_je_hodin <= "17:00")) {
       $po_kolika_minutach = 15;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "17:00") AND ($kolik_je_hodin <= "18:00")) {
       $po_kolika_minutach = 20;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "18:00") AND ($kolik_je_hodin <= "19:00")) {
       $po_kolika_minutach = 25;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "19:00") AND ($kolik_je_hodin <= "20:00")) {
       $po_kolika_minutach = 30;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "20:00") AND ($kolik_je_hodin <= "21:00")) {
       $po_kolika_minutach = 35;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "21:00") AND ($kolik_je_hodin <= "22:00")) {
       $po_kolika_minutach = 40;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "22:00") AND ($kolik_je_hodin <= "23:00")) {
       $po_kolika_minutach = 45;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      } else if (($kolik_je_hodin >= "23:00") AND ($kolik_je_hodin <= "0:00")) {
       die;
      } else {
       $po_kolika_minutach = 15;
       $po_kolika_sekundach = $po_kolika_minutach * 60;
       $po_kolika_sekundach = rand(($po_kolika_sekundach-120),($po_kolika_sekundach+120));
       sleep($po_kolika_sekundach);
      }





     } else {
     
      $end[6] = getTime();
      
     }
     /* kontrola, jestli vše proběhlo v pořádku */
         if ($client->message->faultString){
          echo "Chyba - ".$client->message->faultString."<br />";
         } else {
          //echo "<b>Převedení článku do Wordpressu proběhlo OK.</b><br />";
         }

     //do databáze zaznamenáváme nový poslední titulek------------------------------------------------------------------

     $smaz_to_vsechno = "DELETE FROM poslednivydany";
     $objekt_vysledku = mysqli_query($db_spojeni, $smaz_to_vsechno);

     if (!$objekt_vysledku){
      echo 'Vymazání tabulky poslednivydany se nepodařilo, sorry';
      echo '<br />';
      echo 'Popis chyby: ', mysqli_error($db_spojeni);
      exit();
     }
     
     //escapování apostrofů v sql query
     $titulek_licence = str_replace("'", "''", $titulek_licence);

     //změněno
     $insert = "INSERT INTO poslednivydany (title)
     VALUES ('$titulek_licence')";
     $objekt_vysledku = mysqli_query($db_spojeni, $insert);

     if (!$objekt_vysledku){
      echo 'Poslání SQL příkazu se nepodařilo, sorry';
      echo '<br />';
      echo 'Popis chyby: ', mysqli_error($db_spojeni);
      exit();
     }

    //posíláme logy na mail--------------------------------------------------------------------------------------------------------------
    $po_kolika_poslat_mail = 10;

    if (je_nasobek(($clanku_minule + $pocitadlo_vydavani-1), $po_kolika_poslat_mail) == true){
     try {
	    $mail = new PHPMailer(true); //New instance, with exceptions enabled
      
      $zprava_v_mailu = "Import stránek z Wikipedie do <b>domain.cz</b> probíhá OK.<br />Už je zpracováno<b> " . ($clanku_minule + $pocitadlo_vydavani-1) . " </b>položek z XML.<br />To je <b>" . round((($clanku_minule + $pocitadlo_vydavani-1)/(125829/100)),4) . " %</b>.<br />Importování běží od svého startu <b>" . round(($ubehlo_minule + $end[6] - $start[6]),1) . "</b> sekund = <b>" . round((($ubehlo_minule + $end[6] - $start[6])/60),1) . "</b> minut = <b>" . round(((($ubehlo_minule + $end[6] - $start[6])/60)/60),1) . "</b> hodin = <b>" . round((((($ubehlo_minule + $end[6] - $start[6])/60)/60)/24),1) . "</b> dnů.<br />";
      $jeden_clanek_trva = (($ubehlo_minule + $end[6] - $start[6])/($clanku_minule + $pocitadlo_vydavani-1));
      $zprava_v_mailu = $zprava_v_mailu . "Zpracování jedné položky v XML průměrně trvá <b>" . round($jeden_clanek_trva,1) . "</b> sekund.<br />";
      $do_konce = $jeden_clanek_trva * (125829 - ($clanku_minule + $pocitadlo_vydavani-1));
      $zprava_v_mailu = $zprava_v_mailu . "Odhad jak dlouho by bylo do konce bez sleepu mezi články: <b>" . round(((($do_konce)/60)/60),1) . "</b> hodin = <b>" . round((((($do_konce)/60)/60)/24),1) . "</b> dnů.<br />";
      
      $body = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<title>Email test</title>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n</head>\n<body>\n<p>" . $zprava_v_mailu . "</p>\n</body>\n</html>";

    	$mail->IsSMTP();                           // tell the class to use SMTP
    	$mail->SMTPAuth   = true;                  // enable SMTP authentication
    	$mail->Port       = 465;                    // set the SMTP server port
    	$mail->Host       = "smtp.googlemail.com"; // SMTP server
    	$mail->Username   = "domain.sk.logs@gmail.com";     // SMTP server username
    	$mail->Password   = "nebukadnecar";            // SMTP server password
    	
    	$mail->CharSet    = "utf-8";            // kódování mailu

    	$mail->IsSendmail();  // tell the class to use Sendmail

    	$mail->AddReplyTo("domain.sk.logs@gmail.com","domain.cz log");

    	$mail->From       = "domain.sk.logs@gmail.com";
    	$mail->FromName   = "domain.cz log";

	    $to = "domain.sk.logs@gmail.com";

    	$mail->AddAddress($to);

      //upravit předmět mailu - www stránku
    	$mail->Subject  = "domain.cz log, importováno " . ($clanku_minule + $pocitadlo_vydavani-1) . " stránek";

    	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
    	$mail->WordWrap   = 80; // set word wrap

    	$mail->MsgHTML($body);

    	$mail->IsHTML(true); // send as HTML

    	$mail->Send();
    	//echo "<b>Poslal jsem mail (při vydání" . ($pocitadlo_vydavani-1) . ". článku.</b><br />";
    } catch (phpmailerException $e) {
	     echo $e->errorMessage();
    }
    
   }
   
   //do databáze zaznamenáváme nové poslední uběhlé časy a počet článků------------------------------------------------------------------

     $smaz_to_vsechno = "DELETE FROM posledni";
     $objekt_vysledku = mysqli_query($db_spojeni_cas, $smaz_to_vsechno);

     if (!$objekt_vysledku){
      echo 'Vymazání tabulky posledni se nepodařilo, sorry';
      echo '<br />';
      echo 'Popis chyby: ', mysqli_error($db_spojeni_cas);
      exit();
     }

     $vlozit_cas = $ubehlo_minule + $end[6] - $start[6];
     $vlozit_pocet = $clanku_minule + $pocitadlo_vydavani-1;

     $insert = "INSERT INTO `posledni` (`ubehly_cas`, `ubehly_pocet_cl`)
     VALUES ('$vlozit_cas', '$vlozit_pocet');";
     $objekt_vysledku = mysqli_query($db_spojeni_cas, $insert);

     if (!$objekt_vysledku){
      echo 'Poslání SQL příkazu se nepodařilo, sorry';
      echo '<br />';
      echo 'Popis chyby: ', mysqli_error($db_spojeni_cas);
      exit();
     }

    $end[2] = getTime();

   }

   }

   $xml->read();
   break;
 }

}

$xml->close();

?>