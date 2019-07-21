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


require_once __DIR__ . DIRECTORY_SEPARATOR . 'class.php2asp.php';

/**
 * Function for converting PHP File or Code to ASP
 * 
 * @author      Dr. Simon Antony Roberts <simonxaies@gmail.com>
 * @see         https://github.com/DrARoberts/php2asp
 * @subpackage  php2asp
 */
function convertPHP2ASP($phpfile = '')
{
    return new php2asp($phpfile); 
} 
