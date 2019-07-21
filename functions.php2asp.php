<?php
/**
 * Convert PHP to ASP
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @since           1.0.1
 * @author          Dr. Simon Antony Roberts <simonxaies@gmail.com>
 * @author          Simon Broadley <simonp@influenca.com>
 * @version         1.0.2
 * @description		Convert PHP to ASP
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/DrARoberts/php2asp
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 * 
 */

/**
 * Function for white spacing
 * 
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function white_space($char)
{
	$whitespace=' '.chr(13).chr(10).chr(9).' ';
	for ($i=0; $i<strlen($whitespace); $i++)
	{	
		if ($char==$whitespace[$i])
			$white=1;	
	}	
	return $white; 
} 

/**
 * Function for adding codes at top of array text file
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function add_code_at_top($match)
{
	global $asp_top;
	global $asp_top_code;
	global $asp_top_functions;
	if ($asp_top[$match] && !strpos($asp_top_functions,','.$match.','))
	{
		$asp_top_functions.='!,'.$match.',';
	}
}

/**
 * Function for printing out string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function printout($string)
{
	for ($i=0; $i<strlen($string); $i++)
	{
		echo $string[$i];
		if (ord($string[$i])<32)
			echo '['.ord($string[$i]).']';
	}
} 

/**
 * Function for html decoding
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function htmldecode($string)
{
	$string=str_replace('&gt;}','>',$string);
	$string=str_replace('&lt;','<',$string);
	$string=str_replace('&quot;','"',$string);
	$string=str_replace('&nbsp;',' ',$string);
	$string=str_replace('&amp;','&',$string);
	return $string;
 }  
 
/**
 * Function for finding end string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function findend($end,$current_position)
{
	$new_position=$current_position;
	$endchr=chr(10);
	while ($php_file[$new_position]!=$endchr && $new_position<$end)
	{
		$new_position++;
	}
	return $new_position;
} 

/**
 * Function for triming spaces in string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function trimspace($string)
{
	$string=str_replace(' ','@#!NEWLINE!',$string);
	$string=trim($string);
	$string=str_replace('@#!NEWLINE!',' ',$string);
	return $string;
} 


/**
 * Function for setting functions array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function set_functions()
{
	global $php_functions;
	global $asp_functions;
	global $asp_endings;
	global $asp_top;
	global $asp_bottom;
	global $bracketstack;
	$bracketstack=array();
	$page= fopen(__DIR__ . DS . 'data' . DS . "php2asp__equivlents.html", "r") or die("can't open file");
	$pagestring=fread($page, 3000000);
	fclose ($page);
	$pagestring=str_replace(chr(10),'',$pagestring);
	$pagestring=str_replace(chr(12),'',$pagestring);
	$pagestring=str_replace(' ',' ',$pagestring);
	$pagestring=str_replace('	',' ',$pagestring);
	$pagestring=str_replace(' ',' ',$pagestring);
	$pagestring=str_replace('<br>&nbsp;',chr(10),$pagestring);
	$pagestring=str_replace('<br> ',chr(10),$pagestring);
	$pagestring=str_replace('&quot;','"',$pagestring);
	$pagestring=str_replace('<p>','',$pagestring);
	$pagestring=str_replace('</p>','',$pagestring);
	$pagestring=str_replace('<br>',chr(10),$pagestring);
	$pagestring=str_replace('</td>','',$pagestring);
	$pagestring=str_replace('</tr>','',$pagestring);
	$pagestring=htmldecode($pagestring);
	$rows=explode('<tr>',$pagestring);
	for ($i=2; $i<count($rows); $i++)
	{
		$parts=explode('<td>',$rows[$i]);
		$php_functions[$i-1]=trimspace($parts[1]);
		$asp_functions[$i-1]=trimspace($parts[3]);
		$asp_endings[$i-1]=trim($parts[4]);
		$asp_top[$i-1]=trim($parts[5]);
		$asp_bottom[$i-1]=trim($parts[6]);
	}	
	
}


/**
 * Function for finding functions in string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function find_function($position,$endblockposition)
{	
	global $firstletters;
	$firstletters.=$php_file[$position];
	global $php_functions;
	global $asp_functions;
	global $matchedfunctions;
	global $asp_endings;
	global $php_file;
	global $asp_file;
	global $failure;
	global $parts;
	global $match;
	global $bracketstack;
	$match=0;
	if ($php_file[$position]!='?')
	{
		for ($i=1; $i<count($php_functions)+1; $i++)
		{  
			$n=$position;
			$fn=0;
			$failed=0;
			$overflow=0;
			$match=0;
			while (!$failed && !$match)
			{	
				if($php_file[$n]==$php_functions[$i][$fn] OR strtolower($php_file[$n])==$php_functions[$i][$fn])
				{
					$n++;
					$fn++;
				} else {
					if (white_space($php_file[$n]) && $php_functions[$i][$fn-1]==' ')
					{
						$n++;
					} elseif (white_space($php_functions[$i][$fn])) {
						$fn++;
					} elseif ($php_functions[$i][$fn]=='%') {
						$part_label=$php_functions[$i][$fn];
						$fn=$fn+2;
						while($php_functions[$i][$fn]==' ')
							$fn++;
						$partstart=$n;
						$terminator=$php_functions[$i][$fn];
						$found_terminator=0;
						$inescaped='';
						while(!$found_terminator && $n<strlen($php_file) && !$failed) {
							if ($inescaped) {				
								if ($php_file[$n]==$inescaped[0] && $php_file[$n-1]!='\\') {
									$inescaped=substr($inescaped,1);
								}
							} else {
								if ($php_file[$n]=='"' OR $php_file[$n]=="'")
									$inescaped=$php_file[$n].$inescaped;
								if ($php_file[$n]=='(')
									$inescaped=')'.$inescaped;
								if ($php_file[$n]==$terminator)
								{
									$found_terminator=1;
									$fn++;
									$parts[$part_label]=substr($php_file,$partstart,$n-$partstart);
								}				
								if($part_label=='X' && $php_file[$n]==chr(10)) {
								if ($terminator=='#') {
									$found_terminator=1;
									$fn++;
									$n--;
									$parts[$part_label]=substr($php_file,$partstart,$n-$partstart);
								} else { 
									$failed=1;
								}
							}
						}
						$n++;
					}
				} else {
					$failed=1;
				}
			}
			if ($fn>=strlen($php_functions[$i])) {
				$matchedfunctions++;
				$match=$i;
				if ($asp_endings[$i]) {
					array_push($bracketstack,$asp_endings[$i]);
				} 
				$i=count($php_functions)+1;
			} elseif($n>$endblockposition) {
				$failed=2;
			}
		}
		return $n;
	}
}
 

/**
 * Function for replying boolean flip
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function flip ($var)
{ 
	if ($var)
		$var=0;
	else
		$var=1;
	return $var;
 } 

 
/**
 * Function for finding valid variable in string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function validvar ($char)
{	
	if (ord($char)>64 && ord($char)<91 )	
		$valid=1;
	if (ord($char)>96 && ord($char)<123 )	
		$valid=1;
	if ($char=='_')	$valid=1;
	return $valid;
}


/**
 * Function for swaping php functions for asp in string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function swap_function ($start, $end)
{ 
	global $php_functions;
	global $asp_functions;
	global $matchedfunctions;
	global $bracketstack;
	global $php_file;
	global $asp_file;
	global $asp_endings;
	global $failure;
	global $parts;
	global $match;
	$function_count=0;
	$fn_end=find_function ($start,$end);
	$n=$start;
	if ($match) {
		add_code_at_top($match);
		$thefunction=$asp_functions[$match];
		$partkeys=array_keys($parts);
		for ($i=0; $i<count($partkeys); $i++) { 
			$forlimit=$parts[$partkeys[$i]];
			for ($ip=0; $ip<strlen($forlimit); $ip++) {
				if ($partkeys[$i]!='X') {
					if ($parts[$partkeys[$i]][$ip]=='"') {			
						$parts[$partkeys[$i]]=substr($parts[$partkeys[$i]],0,$ip).'"'.substr($parts[$partkeys[$i]],$ip);
						$ip++;
					} elseif ($parts[$partkeys[$i]][$ip-1]==chr(92)) {
						$parts[$partkeys[$i]][$ip-1]='"';
					} else {
						$indouble=flip($indouble);
					}
					if ($parts[$partkeys[$i]][$ip]=="'") { 
						if ($parts[$partkeys[$i]][$ip-1]==chr(92)) { 
							$parts[$partkeys[$i]]=substr($parts[$partkeys[$i]],0,$ip-1).substr($parts[$partkeys[$i]],$ip);
						} else { 
							$parts[$partkeys[$i]][$ip]='"';
							$insingle=flip($insingle);
						}	 
					} elseif ($parts[$partkeys[$i]][$ip]==".") {
						if (!$insingle && !$indouble) {
							$parts[$partkeys[$i]]=substr($parts[$partkeys[$i]],0,$ip).' & '.substr($parts[$partkeys[$i]],$ip+1);
							$ip=$ip+2;
						}
					} elseif ($parts[$partkeys[$i]][$ip]=="$" && $indouble && validvar($parts[$partkeys[$i]][$ip+1])) {			
						$varname='$';
						$startvar=$ip;
						$ip++;
						while (validvar($parts[$partkeys[$i]][$ip])&& $ip<strlen($parts[$partkeys[$i]])) {
							$varname.=$parts[$partkeys[$i]][$ip];
						}
						$ip++;
					}
				}
				if ($parts[$partkeys[$i]][$ip]=='[') {
					$brackets=0;
					while (!$endofsquarebrackets && $ip<strlen($parts[$partkeys[$i]])) {
						if($parts[$partkeys[$i]][$ip]=='[')
							$brackets++;
					}
					if($parts[$partkeys[$i]][$ip]==']')
						$brackets--;
					$varname.=$parts[$partkeys[$i]][$ip];
					if (!$brackets && $parts[$partkeys[$i]][$ip+1]!='[') 
						$endofsquarebrackets=1;
					$ip++;
				} elseif ($parts[$partkeys[$i]][$ip]=='$' && !$insingle && !$indouble) {
					$ip++;
					while (validvar($parts[$partkeys[$i]][$ip]) && $ip<strlen($parts[$partkeys[$i]])) {
						$ip++;
					}
				} elseif (validvar($parts[$partkeys[$i]][$ip]) && !$insingle && !$indouble) {
					$parts[$partkeys[$i]]=substr($parts[$partkeys[$i]],0,$ip).'@@@function{{{'.substr($parts[$partkeys[$i]],$ip);
					$ip=$ip + strlen('@@@function@@@');
					while (validvar($parts[$partkeys[$i]][$ip])&& $ip<strlen($parts[$partkeys[$i]])) {
						$ip++;
					}
				}
			}		
		}		

	}

	if (strpos('.'.$thefunction,'%'.$partkeys[$i].'%')) {
		$thefunction=str_replace('%'.$partkeys[$i].'%',$parts[$partkeys[$i]],$thefunction);
	} else {
		$loopend=array_pop($bracketstack);
		$loopend=str_replace('%'.$partkeys[$i].'%',$parts[$partkeys[$i]],$loopend);
		array_push($bracketstack,$loopend);
	}
	$parts[$partkeys[$i]]='';
	$asp_file.=$thefunction;
	$asp_file.=chr(10);
 
	if ($php_file[$start]=='}')
	{
		$fn_end=$start+1;
		$asp_file.=array_pop($bracketstack).chr(10);
	} elseif ($failed<2 && $php_file[$fn_end]!='?') {
		while (validvar($php_file[$fn_end]))
			$fn_end++;
	}
	$chars=$fn_end-$start+1;
	$asp_file.=substr($php_file,$start,$chars);
	$failure[]=substr($php_file,$start,$chars);
}	


/**
 * Function for finding pp in string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function find_php()
{
	global $php_file;
	global $asp_file;
	$i=0;
	while($i<strlen($php_file))
	{	
		if ($php_file[$i]=='?' && $php_file[$i-1]=='<') {
			$php=1;
			$asp_file.='% ';
			$i++;
		} elseif ($php_file[$i]=='>' && $php_file[$i-1]=='?') {
			$php=0;
			$asp_file.=chr(10).'%>';
			$i++;
		}
		if ($php) {
			while (white_space($php_file[$i])) {
				$i++;
			}
			$end=$i;
			$foundend=0;
			while (!$foundend) {
				$end++;
				if ($php_file[$end]=='>' && $php_file[$end-1]=='?')
					$foundend=1;
				if ($end>strlen($php_file))
					$foundend=1;
			}
			$i=swap_function ($i,$end);
		} else {
			$asp_file.=$php_file[$i];
		}
		$i++;
	} 
} 


/**
 * Function for finding compounding functions in string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function findcompoundfunctions()
{ 
	echo $php_file;
	global $php_functions;
	global $asp_functions;
	global $bracketstack;
	global $asp_endings;
	global $php_file;
	global $asp_file;
	global $failure;
	global $parts;
	global $match;
	global $failed;
	while ($x=strpos($php_file,'@@@function{{{')) {
		$php_file=substr($php_file,0,$x).substr($php_file,$x+strlen('@@@function{{{'));
		$fn_end=find_function ($x,strlen($php_file));
		if ($match)
		{
			$thefunction=$asp_functions[$match];
			
			$partkeys=array_keys($parts);
			for ($i=0; $i<count($partkeys); $i++) {
				$thefunction=str_replace('%'.$partkeys[$i].'%',$parts[$partkeys[$i]],$thefunction);
			}	
	
			$php_file=substr($php_file,0,$x).$thefunction.substr($php_file,$fn_end);
			add_code_at_top($match);
		} elseif ($failed<2) {
			$failure[]=substr($php_file,$start,$n-$start);
		} 
	} 
} 


/**
 * Function for finding function names in array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function findfunctionname($position)
{	
	global $asp_file,$fn_name_list;
	$lookingfor='Function fn_';
	$length=strlen($lookingfor);
	$n=$position;
	while (!$found && $n>0) {
		if (substr($asp_file,$n,$length)==$lookingfor) {
			$found=$n;
		}
		$n--;
	}	
	$found=$found+$length;
	while (white_space($asp_file[$found]))
		$found++;
	$fn_length=0;
	while (validvar($asp_file[$found+$fn_length]))
		$fn_length++;
	$name=substr($asp_file,$found,$fn_length);
	$fn_name_list.=','.$name;
	return 'fn_'.$name;
} 
 

/**
 * Function for doing returns with the string array
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function doreturns()
{
	global $asp_file;
	$markerlength=strlen('$var_the_function_name');
	$name=findfunctionname($pos);
	$asp_file=substr($asp_file,0,$pos).$name.substr($asp_file,$pos+$markerlength);
} 


/**
 * Function for making string array unique
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function unique($string)
{	
	$string=substr($string,1);
	$str_array=explode(',',$string);
	$string=implode(',',array_unique($str_array));
	return $string;
} 


/**
 * Function for converting PHP code to ASP
 *
 * @author      Simon Broadley <simonp@influenca.com>
 * @see         http://www.me-u.com/php-asp/
 * @subpackage  php2asp
 */
function tracevars($php_file = '')
{	
	global $asp_file,$asp_top_code;
	$defined='';
	$i=0;
	while($i<strlen($asp_file)) { 
		if ($asp_file[$i]=='%' && $asp_file[$i-1]=='<') {
			$asp=1;
		} elseif ($asp_file[$i]=='>' && $asp_file[$i-1]=='%') {
			$asp=0;
			$i++;
		}	
		if ($asp_file[$i]=='"') {
			if (!$inquotes)
				$inquotes=1;
		} elseif($asp_file[$i+1]!='"')
			$inquotes=0;
		else {
			$noquotes=1;
			$i++;
			while($asp_file[$i]=='"') {
				$noquotes=$noquotes*-1;
				$i++;
			}
			if ($noquotes>0)
				$inquotes=1;
		}	
		if ($asp_file[$i]==chr(10) && $asp) {
			while(white_space($asp_file[$i]))
				$i++;
			if (substr($asp_file,$i,9)=='Function ') {
				$infunction=1;
				$local_vars="";
				$global_list=" ";
 -		    } elseif (substr($asp_file,$i,1)=='E') {
	
			} elseif (substr($asp_file,$i,12)=='End Function') { 
				$infunction=0;
				$local_vars=unique($local_vars);
				if ($local_vars) {
					$local_vars='lv_'.str_replace(',',', lv_',$local_vars);
					$plocal_vars=' Dim '.$local_vars;
					$asp_file=substr($asp_file,0,$globalstart).$plocal_vars .substr($asp_file,$globalstart);
				}
				$i=$i+12+strlen($plocal_vars);
			} elseif ($infunction && substr($asp_file,$i,13)=='Global_vars{') {
				$globalstart=$i;
				$i=$i+13;
				while ($asp_file[$i]!='}')
					$i++;
				$nochars=$i-$globalstart-13;
				$global_list.=','.str_replace(' ','',substr($asp_file,$globalstart+13,$nochars)).',';
				$asp_file=substr($asp_file,0,$globalstart).substr($asp_file,$i+1);
				$i=$globalstart-1;
			}
		}	
		if ($asp && !$inquotes) {
			if($asp_file[$i]=='$') {
				$start=$i;
				while(validvar($asp_file[$i]))
					$i++;
				$varname=substr($asp_file,$start,$i-$start);
				while(white_space($asp_file[$i]))
					$i++;
				if($asp_file[$i]=='[') {
					while($asp_file[$i]=='[')
						$asp_file[$i]='(';
					while ($asp_file[$i]!=']')
						$i++;
					$asp_file[$i]=')';
					while(white_space($asp_file[$i]))
						$i++;
			}			
			$array=1;
		} else {
			$array=0;
		}
		if ($asp_file[$i]=='+' OR $asp_file[$i]=='-' OR $asp_file[$i]=='*' OR $asp_file[$i]=='/' OR $asp_file[$i]=='%') {
			$type[$varname]='math';
		} elseif ($asp_file[$i]=='=') {
			$defined.=','.$varname;
		}
		if ($array)
			$varname.='()';
		if ($infunction && !strpos($global_list,',$'.$varname.',')) {
			$local_vars.=','.$varname;
			$asp_file=substr($asp_file,0,$start-1).'lv_'.substr($asp_file,$start);
		} else {
			$global_vars.=','.$varname;
			$asp_file=substr($asp_file,0,$start-1).'gv_'.substr($asp_file,$start);
		}
		}
		$i++;
	}	
	$global_vars=unique ($global_vars);
	$global_vars='gv_'.str_replace(',',', gv_',$global_vars);
	if (strlen (trim($global_vars))>3) 
		$global_vars='Dim '.$global_vars;
	else 
		$global_vars='';
	$asp_top_code=$global_vars.$asp_top_code;
	$php_file=stripslashes($php_file);
	$php_file=str_replace('}}?>',' ?>',$php_file);
	$php_file=str_replace('<?','<? ',$php_file);
	set_functions();
	$original_file=$php_file;
	$php_file=$asp_file;
	findcompoundfunctions();
	doreturns();
	tracevars();
	if ($asp_file[0]=='<' && $asp_file[1]=='%')
		$asp_file='<%'.chr(10).$asp_top_code.chr(10).substr($asp_file,2);
	else
		$asp_file='<%'.chr(10).$asp_top_code.'%>'.chr(10).$asp_file;
	if ($failure) {
		for ($z=0; $z<=count($failure); $z++) {
			if (trim($failure[$z]))
				$out.= $failure[$z].'<br>';
		}
	}	
	$fns=explode(',',$fn_name_list);
	for ($fnc=1; $fnc<=count($fns); $fnc++) {
		$asp_file=str_replace(' '.$fns[$fnc].'(',' fn_'.$fns[$fnc].'(',$asp_file);
		$asp_file=str_replace(' '.$fns[$fnc].' (',' fn_'.$fns[$fnc].'(',$asp_file);
		$asp_file=str_replace('	'.$fns[$fnc].'(',' fn_'.$fns[$fnc].'(',$asp_file);
		$asp_file=str_replace('	'.$fns[$fnc].' (',' fn_'.$fns[$fnc].'(',$asp_file);
		$asp_file=str_replace(' '.$fns[$fnc].'(',' fn_'.$fns[$fnc].'(',$asp_file);
		$asp_file=str_replace(' '.$fns[$fnc].' (',' fn_'.$fns[$fnc].'(',$asp_file);
	} 
	$asp_file=str_replace(' fn_(',' (',$asp_file);
	return $asp_file;
    }
} 

?>