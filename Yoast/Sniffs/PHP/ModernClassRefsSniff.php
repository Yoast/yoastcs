<?php
/**
 * PHPCSExtra, a collection of sniffs and standards for use with PHP_CodeSniffer.
 *
 * @package   PHPCSExtra
 * @copyright 2020 PHPCSExtra Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSExtra
 */

namespace PHPCSExtra\Universal\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * @todo Explain what the sniff does.
 */
class ModernClassRefsSniff implements Sniff
{
  
    private $targetFunctions = [
        'get_class'        => true,
        'get_called_class' => true,
      ...
    ];
  
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function register()
    {
        return [
          T_CLASS_C,
          T_STRING,
          T_NAMESPACE,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 1.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
      $tokens = $phpcsFile->getTokens();

      // Verify whether we are in the same file $phpcsFile->getFilename() as the previous time we saw a namespace token.
      // If not: clear the recorded namespace.
      // Needs: property to track the name of the current file.      

      
      if ($tokens[$stackPtr]['code'] === T_CLASS_C) {
        // Throw an error
        // Auto-fix to `self::class`.
        return;        
      }
      
      if ($tokens[$stackPtr]['code'] === T_NAMESPACE) {
        // Check the token is a namespace declaration (not a use of the token as an operator, i.e. not followed by a backslash)
        // If it is a declaration, get the namespace name.
        // Save the namespace name to a private property.
        return;        
      }
      
      // Okay, so this must a T_STRING.
      $nameLc = strtolower($tokens[$stackPtr]['content']);
      if (isset($this->targetFunctions[$nameLc]) === false) {
        // Not one of the functions we are looking for.
        return;
      }
      
      /*
      - Is this a global function call ?
        -> next non empty token is an open parenthesis.
        -> previous non empty token:
           -> backslash -> look at previous non-empty token before that and make sure it's not a T_STRING
           -> backslash -> if the previous non-empty token is a T_NAMESPACE token -> check if this is a namespaced file.
           -> double colon -> bow out, not a global function call
           -> object operator `->` bow out, not a global function call
           -> nullsafe object operator `?->` bow out, not a global function call 
           -> function keyword (and make sure to jump over references `&`) ->  bow out, not a global function call
      
      - Analyse the actual function call:
         => analyse the function call and retrieve each individual parameter
         => get the parameter(s) we need

         - get_class()
          -> without parameter: throw an error + fix => replace complete function call with self::class
          -> with a parameter/parameters (in this case the first one if positional or the one named `object` if named):
             - parameter is `null` => treat the same as without parameter.
             - parameter is a variable and only a variable and the variable itself is `$this`, throw error + fix => replace complete function with static::class
             - if the target parameter is a variable, but not `$this` or is not a variable => bow out
         
         - get_called_class()
           -> expects no params, if there are parameters -> throw a warning & return (don't auto-fix)
           -> without parameter: throw error + fix & replace complete function call with static::class
           
           
      
      */
      
      
      
    }
}
