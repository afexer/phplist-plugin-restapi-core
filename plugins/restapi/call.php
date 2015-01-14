<?php

namespace phpListRestapi;

defined('PHPLISTINIT') || die;

//No HTML-output, please!
ob_end_clean();

//Getting phpList globals for this plugin
$plugin = $GLOBALS['plugins'][$_GET['pi']];

include 'includes/response.php';
include 'includes/pdo.php';

include 'includes/common.php';

include 'includes/actions.php';
include 'includes/lists.php';
include 'includes/subscribers.php';
include 'includes/templates.php';
include 'includes/messages.php';

if (function_exists('api_request_log'))
{
    api_request_log();
}

// Check if this is called outside phpList auth, this should never occur!
if ( empty( $plugin->coderoot ) )
{
    Response::outputErrorMessage( 'Not authorized! Please login with [login] and [password] as admin first!' );
}

// Check if command is empty!
$cmd = $_REQUEST['cmd'];
$cmd = preg_replace('/\W/','',$cmd);
if ( empty($cmd) ){
    Response::outputMessage('OK! For action, please provide Post Param Key [cmd] !');
}

// Try calling the requested method on all of the available classes
// NOTE: This looks inefficient
if ( is_callable( array( 'phpListRestapi\Lists',       $cmd ) ) ) Lists::$cmd();
if ( is_callable( array( 'phpListRestapi\Actions',     $cmd ) ) ) Actions::$cmd();
if ( is_callable( array( 'phpListRestapi\Subscribers', $cmd ) ) ) Subscribers::$cmd();
if ( is_callable( array( 'phpListRestapi\Templates',   $cmd ) ) ) Templates::$cmd();
if ( is_callable( array( 'phpListRestapi\Messages',    $cmd ) ) ) Messages::$cmd();

//If no command found, return error message!
Response::outputErrorMessage( 'No function for provided [cmd] found!' );
