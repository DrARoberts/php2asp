 <?php
/**
 * Convert PHP to ASP
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors . 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE . 
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202 . 0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl . html)
 * @since           1.0.3
 * @author          Dr. Simon Roberts  <simonxaies@gmail.com> 
 * @author          Simon Broadley  <simonp@influenca.com > 
 * @version         1.0.3
 * @description		Convert PHP to ASP
 * @link            http://internetfounder . wordpress . com
 * @link            https://github.com/DrARoberts/php2asp
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 * 
 */




 
 /**
  * Class for converting PHP to ASP
  *
  * @author      Dr. Simon Roberts  <simonxaies@gmail.com> 
  * @see         https://github.com/DrARoberts/php2asp
  * @subpackage  php2asp
  */
class php2asp {
    
    /**
     * @var $string
     */
    var $equivalents = __DIR__ . DIRECTORY_SEPARATOR . "php2asp__equivlents.html";
    
    /**
     * @var $string
     */
    var $asp_file = '';
    
    /**
     * @var $string
     */
    var $php_file = '';
    
    /**
     * @var $unknown
     */
    var $asp_top_code = '';

    /**
     * @var $unknown
     */
    var $asp_top = '';
    
    /**
     * @var $array
     */
    var $asp_top_functions = array();
    
    /**
     * @var $unknown
     */
    var $asp_bottom = '';
    
    /**
     * @var $array
     */
    var $php_functions = array();

    /**
     * @var $unknown
     */
    var $asp_functions = '';

    /**
     * @var $unknown
     */
    var $asp_endings = '';
   
    /**
     * @var $array
     */
    var $bracketstack = array();

    /**
     * @var $array
     */
    var $failure = array();

    /**
     * @var $array
     */
    var $parts = array();
    
    /**
     * @var $integer
     */
    var $match = '';
    
    /**
     * @var $integer
     */
    var $failed;
    
    /**
     * @var $string
     */
    var $firstletters = '';

    /**
     * @var $unknown
     */
    var $matchedfunctions;
    
    /**
     * Function for class construction
     *
     * @author      Dr. Simon Roberts  <simonxaies@gmail.com> 
     * @see         https://github.com/DrARoberts/php2asp
     * @subpackage  php2asp
     */
    public function __construct($phpfile = '') {
        if (file_exists($phpfile)) {
            $this->setPHP(file_get_contents($phpfile));
        } elseif (strlen($phpfile) > 0)
            $this->setPHP($phpfile);
        $this->set_functions();
        if (!strlen($this->php_file)) {
            $this->tracevars();
            return $this->getASP();
        }
    }

    /**
     * Function for firing php2asp calculation
     *
     * @author      Dr. Simon Roberts  <simonxaies@gmail.com>
     * @see         https://github.com/DrARoberts/php2asp
     * @subpackage  php2asp
     */
    public function calc($phpfile = '') {
        if (file_exists($phpfile)) {
            $this->setPHP(file_get_contents($phpfile));
        } elseif (strlen($phpfile) > 0)
            $this->setPHP($phpfile);
        $this->set_functions();
        if (!strlen($this->php_file)) {
            $this->tracevars();
            return $this->getASP();
        }
    }
    
    /**
     * Function for getting ASP
     *
     * @author      Dr. Simon Roberts  <simonxaies@gmail.com>
     * @see         https://github.com/DrARoberts/php2asp
     * @subpackage  php2asp
     */
    public function getASP($bufferout = true) {
        if ($bufferout == true)
            return $this->bufferout($this->asp_file);
        return $this->asp_file;
    }
    
    /**
     * Function for setting PHP
     *
     * @author      Dr. Simon Roberts  <simonxaies@gmail.com>
     * @see         https://github.com/DrARoberts/php2asp
     * @subpackage  php2asp
     */
    public function setPHP($phpcode = '') {
        $this->php_file = $phpcode;
    }
    
    /**
     * Function for white spacing
     * 
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function white_space($char)
    {
    	$whitespace = ' ' . chr(13) . chr(10) . chr(9) . ' ';
    	for ($i = 0; $i < strlen($whitespace); $i++)
    	{	
    		if ($char == $whitespace[$i])
    			$white = 1;	
    	}	
    	return $white; 
    } 
    
    /**
     * Function for adding codes at top of array text file
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function add_code_at_top($match)
    {
        if ($this->asp_top[$match] && !strpos($this->asp_top_functions, ',' . $match . ', '))
    	{
    	    $this->asp_top_functions .=  '!, ' . $match . ', ';
    	}
    }
    
    /**
     * Function for buffering a string out as asp from string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function bufferout($string)
    {
        $result = '';
    	for ($i = 0; $i < strlen($string); $i++)
    	{
    	    $result .= $string[$i];
    		if (ord($string[$i]) < 32)
    		    $result .= '[' . ord($string[$i]) . ']';
    	}
    	return $result;
    } 
    
    /**
     * Function for html decoding
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function htmldecode($string)
    {
    	return html_entity_decode($string);
     }  
     
    /**
     * Function for finding end string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function findend($end, $current_position)
    {
    	$new_position = $current_position;
    	$endchr = chr(10);
    	while ($this->php_file[$new_position] != $endchr && $new_position < $end)
    	{
    		$new_position++;
    	}
    	return $new_position;
    } 
    
    /**
     * Function for triming spaces in string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function trimspace($string)
    {
    	$string = str_replace(' ', '@#!NEWLINE!', $string);
    	$string = trim($string);
    	$string = str_replace('@#!NEWLINE!', ' ', $string);
    	return $string;
    } 
    
    
    /**
     * Function for setting functions array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function set_functions()
    {
    	$this->bracketstack = array();
    	$page =  fopen($this->equivalents, "r") or die("can't open file");
    	$pagestring = fread($page, 3000000);
    	fclose ($page);
    	$pagestring = str_replace(chr(10), '', $pagestring);
    	$pagestring = str_replace(chr(12), '', $pagestring);
    	$pagestring = str_replace(' ', ' ', $pagestring);
    	$pagestring = str_replace('	', ' ', $pagestring);
    	$pagestring = str_replace(' ', ' ', $pagestring);
    	$pagestring = str_replace('<br>&nbsp;',chr(10), $pagestring);
    	$pagestring = str_replace('<br>',chr(10), $pagestring);
    	$pagestring = str_replace('&quot;', '"', $pagestring);
    	$pagestring = str_replace('<p>', '', $pagestring);
    	$pagestring = str_replace('</p>', '', $pagestring);
    	$pagestring = str_replace(array('<br>', '<br/>', '<br />'),chr(10), $pagestring);
    	$pagestring = str_replace('</td>', '', $pagestring);
    	$pagestring = str_replace('</tr>', '', $pagestring);
    	$pagestring = $this->htmldecode($pagestring);
    	$rows = explode('<tr>', $pagestring);
    	for ($i = 2; $i < count($rows); $i++)
    	{
    		$parts = explode('<td > ', $rows[$i]);
    		$this->php_functions[$i - 1] = trimspace($this->parts[1]);
    		$this->asp_functions[$i - 1] = trimspace($this->parts[3]);
    		$this->asp_endings[$i - 1] = trim($this->parts[4]);
    		$this->asp_top[$i - 1] = trim($this->parts[5]);
    		$this->asp_bottom[$i - 1] = trim($this->parts[6]);
    	}	
    	
    }
    
    
    /**
     * Function for finding functions in string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function find_function($position, $endblockposition)
    {	
    	$this->firstletters .=  $this->php_file[$position];
    	$this->match = 0;
    	if ($this->php_file[$position] != '?') {
    		for ($i = 1; $i < count($this->php_functions) + 1; $i++) {  
    			$n = $position;
    			$fn = 0;
    			$this->failed = 0;
    			$overflow = 0;
    			$this->match = 0;
    			while (!$this->failed && !$this->match)	{	
    				if($this->php_file[$n] == $this->php_functions[$i][$fn] OR strtolower($this->php_file[$n]) == $this->php_functions[$i][$fn]) {
    					$n++;
    					$fn++;
    				} else {
    					if ($this->white_space($this->php_file[$n]) && $this->php_functions[$i][$fn - 1] == ' ')
    					{
    						$n++;
    					} elseif ($this->white_space($this->php_functions[$i][$fn])) {
    						$fn++;
    					} elseif ($this->php_functions[$i][$fn] == '%') {
    						$part_label = $this->php_functions[$i][$fn];
    						$fn = $fn+2;
    						while($this->php_functions[$i][$fn] == ' ')
    							$fn++;
    						$partstart = $n;
    						$terminator = $this->php_functions[$i][$fn];
    						$found_terminator = 0;
    						$inescaped = '';
    						while(!$found_terminator && $n < strlen($this->php_file) && !$this->failed) {
    							if ($inescaped) {				
    								if ($this->php_file[$n] == $inescaped[0] && $this->php_file[$n - 1] != '\\') {
    									$inescaped = substr($inescaped, 1);
    								}
    							} else {
    								if ($this->php_file[$n] == '"' OR $this->php_file[$n] == "'")
    									$inescaped = $this->php_file[$n] . $inescaped;
    								if ($this->php_file[$n] == '(')
    									$inescaped = ')' . $inescaped;
    								if ($this->php_file[$n] == $terminator) {
    									$found_terminator = 1;
    									$fn++;
    									$this->parts[$part_label] = substr($this->php_file, $partstart, $n - $partstart);
    								}				
    								if($part_label == 'X' && $this->php_file[$n] == chr(10)) {
        								if ($terminator == '#') {
        									$found_terminator = 1;
        									$fn++;
        									$n--;
        									$this->parts[$part_label] = substr($this->php_file, $partstart, $n - $partstart);
        								} else { 
        									$this->failed = 1;
        								}
        							}
        						}
        						$n++;
        					}
        				} else {
        					$this->failed = 1;
        				}
        			}
        			if ($fn >= strlen($this->php_functions[$i])) {
        				$this->matchedfunctions++;
        				$this->match = $i;
        				if ($this->asp_endings[$i]) {
        					array_push($this->bracketstack, $this->asp_endings[$i]);
        				} 
        				$i = count($this->php_functions) + 1;
        			} elseif ($n > $endblockposition) {
        				$this->failed = 2;
        			}
        		}
    		}
        	return $n;
        }
    }
     
    /**
     * Function for replying boolean $this->flip
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function flip ($var)
    { 
    	if ($var)
    		$var = 0;
    	else
    		$var = 1;
    	return $var;
     } 
    
     
    /**
     * Function for finding valid variable in string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function validvar ($char)
    {	
    	if (ord($char) > 64 && ord($char) < 91 )	
    		$valid = 1;
    	if (ord($char) > 96 && ord($char) < 123 )	
    		$valid = 1;
    	if ($char == '_')	$valid = 1;
    	return $valid;
    }
    
    
    /**
     * Function for swaping php functions for asp in string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function swap_function ($start, $end)
    { 
    	$function_count = 0;
    	$fn_end = find_function ($start, $end);
    	$n = $start;
    	if ($this->match) {
    		$this->add_code_at_top($this->match);
    		$thefunction = $this->asp_functions[$this->match];
    		$partkeys = array_keys($parts);
    		for ($i = 0; $i < count($partkeys); $i++) { 
    			$forlimit = $this->parts[$partkeys[$i]];
    			for ($ip = 0; $ip < strlen($forlimit); $ip++) {
    				if ($partkeys[$i] != 'X') {
    					if ($this->parts[$partkeys[$i]][$ip] == '"') {			
    						$this->parts[$partkeys[$i]] = substr($this->parts[$partkeys[$i]], 0, $ip) . '"' . substr($this->parts[$partkeys[$i]], $ip);
    						$ip++;
    					} elseif ($this->parts[$partkeys[$i]][$ip - 1] == chr(92)) {
    						$this->parts[$partkeys[$i]][$ip - 1] = '"';
    					} else {
    						$indouble = $this->flip($indouble);
    					}
    					if ($this->parts[$partkeys[$i]][$ip] == "'") { 
    						if ($this->parts[$partkeys[$i]][$ip - 1] == chr(92)) { 
    							$this->parts[$partkeys[$i]] = substr($this->parts[$partkeys[$i]], 0, $ip - 1) . substr($this->parts[$partkeys[$i]], $ip);
    						} else { 
    							$this->parts[$partkeys[$i]][$ip] = '"';
    							$insingle = $this->flip($insingle);
    						}	 
    					} elseif ($this->parts[$partkeys[$i]][$ip] == ".") {
    						if (!$insingle && !$indouble) {
    							$this->parts[$partkeys[$i]] = substr($this->parts[$partkeys[$i]], 0, $ip) . '&' . substr($this->parts[$partkeys[$i]], $ip + 1);
    							$ip = $ip+2;
    						}
    					} elseif ($this->parts[$partkeys[$i]][$ip] == "$" && $indouble && $this->validvar($this->parts[$partkeys[$i]][$ip + 1])) {			
    						$varname = '$';
    						$startvar = $ip;
    						$ip++;
    						while ($this->validvar($this->parts[$partkeys[$i]][$ip])&& $ip < strlen($this->parts[$partkeys[$i]])) {
    							$varname .=  $this->parts[$partkeys[$i]][$ip];
    						}
    						$ip++;
    					}
    				}
    				if ($this->parts[$partkeys[$i]][$ip] == '[') {
    					$brackets = 0;
    					while (!$endofsquarebrackets && $ip < strlen($this->parts[$partkeys[$i]])) {
    						if($this->parts[$partkeys[$i]][$ip] == '[')
    							$brackets++;
    					}
    					if($this->parts[$partkeys[$i]][$ip] == ']')
    						$brackets--;
    					$varname .=  $this->parts[$partkeys[$i]][$ip];
    					if (!$brackets && $this->parts[$partkeys[$i]][$ip + 1] != '[') 
    						$endofsquarebrackets = 1;
    					$ip++;
    				} elseif ($this->parts[$partkeys[$i]][$ip] == '$' && !$insingle && !$indouble) {
    					$ip++;
    					while ($this->validvar($this->parts[$partkeys[$i]][$ip]) && $ip < strlen($this->parts[$partkeys[$i]])) {
    						$ip++;
    					}
    				} elseif ($this->validvar($this->parts[$partkeys[$i]][$ip]) && !$insingle && !$indouble) {
    					$this->parts[$partkeys[$i]] = substr($this->parts[$partkeys[$i]], 0, $ip) . '@@@function{{{' . substr($this->parts[$partkeys[$i]], $ip);
    					$ip = $ip + strlen('@@@function@@@');
    					while ($this->validvar($this->parts[$partkeys[$i]][$ip])&& $ip < strlen($this->parts[$partkeys[$i]])) {
    						$ip++;
    					}
    				}
    			}		
    		}		
    
    	}
    
    	if (strpos(' . ' . $thefunction, '%' . $partkeys[$i] . '%')) {
    		$thefunction = str_replace('%' . $partkeys[$i] . '%', $this->parts[$partkeys[$i]], $thefunction);
    	} else {
    		$loopend = array_pop($this->bracketstack);
    		$loopend = str_replace('%' . $partkeys[$i] . '%', $this->parts[$partkeys[$i]], $loopend);
    		array_push($this->bracketstack, $loopend);
    	}
    	$this->parts[$partkeys[$i]] = '';
    	$this->asp_file .=  $thefunction;
    	$this->asp_file .= chr(10);
     
    	if ($this->php_file[$start] == '}')
    	{
    		$fn_end = $start + 1;
    		$this->asp_file .= array_pop($this->bracketstack) . chr(10);
    	} elseif ($this->failed < 2 && $this->php_file[$fn_end] != '?') {
    		while ($this->validvar($this->php_file[$fn_end]))
    			$fn_end++;
    	}
    	$chars = $fn_end - $start + 1;
    	$this->asp_file .=  substr($this->php_file, $start, $chars);
    	$this->failure[] = substr($this->php_file, $start, $chars);
    }	
    
    
    /**
     * Function for finding pp in string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function find_php()
    {
    	$i = 0;
    	while($i < strlen($this->php_file))
    	{	
    		if ($this->php_file[$i] == '?' && $this->php_file[$i - 1] == '<') {
    			$php = 1;
    			$this->asp_file .=  '% ';
    			$i++;
    		} elseif ($this->php_file[$i] == ' > ' && $this->php_file[$i - 1] == '?') {
    			$php = 0;
    			$this->asp_file .= chr(10) . '%>';
    			$i++;
    		}
    		if ($php) {
    			while ($this->white_space($this->php_file[$i])) {
    				$i++;
    			}
    			$end = $i;
    			$foundend = 0;
    			while (!$foundend) {
    				$end++;
    				if ($this->php_file[$end] == ' > ' && $this->php_file[$end - 1] == '?')
    					$foundend = 1;
    				if ($end > strlen($this->php_file))
    					$foundend = 1;
    			}
    			$i = $this->swap_function ($i, $end);
    		} else {
    			$this->asp_file .=  $this->php_file[$i];
    		}
    		$i++;
    	} 
    } 
    
    
    /**
     * Function for finding compounding functions in string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function findcompoundfunctions()
    { 
    	while ($x = strpos($this->php_file, '@@@function{{{')) {
    		$this->php_file = substr($this->php_file, 0, $x) . substr($this->php_file, $x+strlen('@@@function{{{'));
    		$fn_end = find_function ($x, strlen($this->php_file));
    		if ($this->match)
    		{
    			$thefunction = $this->asp_functions[$this->match];
    			
    			$partkeys = array_keys($parts);
    			for ($i = 0; $i < count($partkeys); $i++) {
    				$thefunction = str_replace('%' . $partkeys[$i] . '%', $this->parts[$partkeys[$i]], $thefunction);
    			}	
    	
    			$this->php_file = substr($this->php_file, 0, $x) . $thefunction . substr($this->php_file, $fn_end);
    			$this->add_code_at_top($this->match);
    		} elseif ($this->failed < 2) {
    			$this->failure[] = substr($this->php_file, $start, $n - $start);
    		} 
    	} 
    } 
    
    
    /**
     * Function for finding function names in array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function findfunctionname($position)
    {	
    	$lookingfor = 'Function fn_';
    	$length = strlen($lookingfor);
    	$n = $position;
    	while (!$found && $n > 0) {
    		if (substr($this->asp_file, $n, $length) == $lookingfor) {
    			$found = $n;
    		}
    		$n--;
    	}	
    	$found = $found + $length;
    	while ($this->white_space($this->asp_file[$found]))
    		$found++;
    	$fn_length = 0;
    	while ($this->validvar($this->asp_file[$found + $fn_length]))
    		$fn_length++;
    	$name = substr($this->asp_file, $found, $fn_length);
    	$fn_name_list .=  ',' . $name;
    	return 'fn_' . $name;
    } 
     
    
    /**
     * Function for doing returns with the string array
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function doreturns()
    {
    	$markerlength = strlen('$var_the_function_name');
    	$name = $this->findfunctionname($pos);
    	$this->asp_file = substr($this->asp_file, 0, $pos) . $name . substr($this->asp_file, $pos + $markerlength);
    } 
    
    
    /**
     * Function for making string array $this->unique
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function unique($string)
    {	
    	$string = substr($string, 1);
    	$str_array = explode(',', $string);
    	$string = implode(',',array_unique($str_array));
    	return $string;
    } 
    
    
    /**
     * Function for converting PHP code to ASP
     *
     * @author      Simon Broadley  <simonp@influenca.com> 
     * @see         http://www.me-u.com/php-asp/
     * @subpackage  php2asp
     */
    private function tracevars()
    {	
    	$defined = '';
    	$i = 0;
    	while($i < strlen($this->asp_file)) { 
    		if ($this->asp_file[$i] == '%' && $this->asp_file[$i - 1] == '<') {
    			$asp = 1;
    		} elseif ($this->asp_file[$i] == ' > ' && $this->asp_file[$i - 1] == '%') {
    			$asp = 0;
    			$i++;
    		}	
    		if ($this->asp_file[$i] == '"') {
    			if (!$inquotes)
    				$inquotes = 1;
    		} elseif ($this->asp_file[$i + 1] != '"')
    			$inquotes = 0;
    		else {
    			$noquotes = 1;
    			$i++;
    			while($this->asp_file[$i] == '"') {
    				$noquotes = $noquotes* - 1;
    				$i++;
    			}
    			if ($noquotes > 0)
    				$inquotes = 1;
    		}	
    		if ($this->asp_file[$i] == chr(10) && $asp) {
    			while($this->white_space($this->asp_file[$i]))
    				$i++;
    			if (substr($this->asp_file, $i, 9) == 'Function ') {
    				$infunction = 1;
    				$local_vars = "";
    				$global_list = " ";
     -		    } elseif (substr($this->asp_file, $i, 1) == 'E') {
    	
    			} elseif (substr($this->asp_file, $i, 12) == 'End Function') { 
    				$infunction = 0;
    				$local_vars = $this->unique($local_vars);
    				if ($local_vars) {
    					$local_vars = 'lv_' . str_replace(',', ', lv_', $local_vars);
    					$plocal_vars = ' Dim ' . $local_vars;
    					$this->asp_file = substr($this->asp_file, 0, $globalstart) . $plocal_vars . substr($this->asp_file, $globalstart);
    				}
    				$i = $i + 12+strlen($plocal_vars);
    			} elseif ($infunction && substr($this->asp_file, $i, 13) == 'Global_vars{') {
    				$globalstart = $i;
    				$i = $i + 13;
    				while ($this->asp_file[$i] != '}')
    					$i++;
    				$nochars = $i - $globalstart - 13;
    				$global_list .=  ',' . str_replace(' ', '', substr($this->asp_file, $globalstart + 13, $nochars)) . ', ';
    				$this->asp_file = substr($this->asp_file, 0, $globalstart) . substr($this->asp_file, $i + 1);
    				$i = $globalstart - 1;
    			}
    		}	
    		if ($asp && !$inquotes) {
    			if($this->asp_file[$i] == '$') {
    				$start = $i;
    				while($this->validvar($this->asp_file[$i]))
    					$i++;
    				$varname = substr($this->asp_file, $start, $i - $start);
    				while($this->white_space($this->asp_file[$i]))
    					$i++;
    				if($this->asp_file[$i] == '[') {
    					while($this->asp_file[$i] == '[')
    						$this->asp_file[$i] = '(';
    					while ($this->asp_file[$i] != ']')
    						$i++;
    					$this->asp_file[$i] = ')';
    					while($this->white_space($this->asp_file[$i]))
    						$i++;
    			}			
    			$array = 1;
    		} else {
    			$array = 0;
    		}
    		if ($this->asp_file[$i] == '+' OR $this->asp_file[$i] == '-' OR $this->asp_file[$i] == '*' OR $this->asp_file[$i] == '/' OR $this->asp_file[$i] == '%') {
    			$type[$varname] = 'math';
    		} elseif ($this->asp_file[$i] == '=') {
    			$defined .=  ',' . $varname;
    		}
    		if ($array)
    			$varname .=  '()';
    		if ($infunction && !strpos($global_list, ', $' . $varname . ', ')) {
    			$local_vars .=  ',' . $varname;
    			$this->asp_file = substr($this->asp_file, 0, $start - 1) . 'lv_' . substr($this->asp_file, $start);
    		} else {
    			$global_vars .=  ',' . $varname;
    			$this->asp_file = substr($this->asp_file, 0, $start - 1) . 'gv_' . substr($this->asp_file, $start);
    		}
    		}
    		$i++;
    	}	
    	$global_vars = $this->unique ($global_vars);
    	$global_vars = 'gv_' . str_replace(',', ', gv_', $global_vars);
    	if (strlen (trim($global_vars)) > 3) 
    		$global_vars = 'Dim ' . $global_vars;
    	else 
    		$global_vars = '';
    	$this->asp_top_code = $global_vars . $this->asp_top_code;
    	$this->php_file = stripslashes($this->php_file);
    	$this->php_file = str_replace('}}? >', '?>', $this->php_file);
    	$this->php_file = str_replace('<?', '<?', $this->php_file);
    	$original_file = $this->php_file;
    	$this->php_file = $this->asp_file;
    	$this->findcompoundfunctions();
    	$this->doreturns();
    	$this->tracevars();
    	if ($this->asp_file[0] == '<' && $this->asp_file[1] == '%')
    		$this->asp_file = '<%' . chr(10) . $this->asp_top_code . chr(10) . substr($this->asp_file, 2);
    	else
    		$this->asp_file = '<%' . chr(10) . $this->asp_top_code . '%>' . chr(10) . $this->asp_file;
    	if ($this->failure) {
    		for ($z = 0; $z <= count($this->failure); $z++) {
    			if (trim($this->failure[$z]))
    				$out .= $this->failure[$z] . '<br />';
    		}
    	}	
    	$fns = explode(',', $fn_name_list);
    	for ($fnc = 1; $fnc <= count($fns); $fnc++) {
    		$this->asp_file = str_replace(' ' . $fns[$fnc] . '(', ' fn_' . $fns[$fnc] . '(', $this->asp_file);
    		$this->asp_file = str_replace(' ' . $fns[$fnc] . ' (', ' fn_' . $fns[$fnc] . '(', $this->asp_file);
    		$this->asp_file = str_replace('	' . $fns[$fnc] . '(', ' fn_' . $fns[$fnc] . '(', $this->asp_file);
    		$this->asp_file = str_replace('	' . $fns[$fnc] . ' (', ' fn_' . $fns[$fnc] . '(', $this->asp_file);
    		$this->asp_file = str_replace(' ' . $fns[$fnc] . '(', ' fn_' . $fns[$fnc] . '(', $this->asp_file);
    		$this->asp_file = str_replace(' ' . $fns[$fnc] . ' (', ' fn_' . $fns[$fnc] . '(', $this->asp_file);
    	} 
    	$this->asp_file = str_replace(' fn_(', ' (', $this->asp_file);
    } 
}
 