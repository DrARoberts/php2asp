# Convert PHP to ASP - php2asp

This conversion utility will not translate everything, but it will still save you a lot of time migrating from PHP to ASP. If you are brand new to ASP, and are attempting to use this tool because you don't want to learn ASP, our advice is to still get a good ASP book, as you will need to do a few manual tweaks.

## Authors: 		Simon Broadley <simonp@influenca.com>; Dr. Simon Antony Roberts <simonxaies@gmail.com>;

### Version:		1.0.3 (Stable)

#### Origin URL:	http://www.me-u.com/php-asp/

#### GitHub URL:	https://github.com/DrARoberts/php2asp

## Executing the subroutines

The following function is found in ./functions.php2asp.php; all you have to do is call either php code or a file into the function and it will output it as ASP

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