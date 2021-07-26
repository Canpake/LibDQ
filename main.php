<?php 
/* The two lines below load the LibDQ library; 
 * An instance of the class is assigned as the variable $lib.
 *
 * NOTE: In the QBM, you would need to replace these two lines with 
 *       this instead: $lib = _MD('lib_dq'); 
 */
require_once('lib_dq.class.php');
$lib = new Lib_DQ();

// You can now call all LibDQ functions as usual. 
// You can find documentation for the functions implemented here: 
// https://canpake.github.io/3-coding-techniques/using-libraries/libdq/

// Here's an example! This prints out whether the DQ library has been loaded.
$lib::ping();
