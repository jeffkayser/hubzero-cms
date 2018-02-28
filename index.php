<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright 2005-2019 HUBzero Foundation, LLC.
 * @license    http://opensource.org/licenses/MIT MIT
 */

/*
|--------------------------------------------------------------------------
| Parent Flag
|--------------------------------------------------------------------------
|
| Set flag that this is a parent file.
|
*/
define('_HZEXEC_', 1);
define('DS', DIRECTORY_SEPARATOR);

/*
|--------------------------------------------------------------------------
| Define directories
|--------------------------------------------------------------------------
|
| First thing we need to do is set some constants for the app's directory
| and the path to the parent directory containing the app and core.
|
*/

// Define the root. Typically, this is the current directory.

if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'])
{
	define('PATH_ROOT', $_SERVER['DOCUMENT_ROOT']);
}
else
{
	define('PATH_ROOT', __DIR__);
}


// Define core. Typically, this is a directory under root but can be
// changed via an environment variable.

if (isset($_ENV['PATH_CORE']) && $_ENV['PATH_CORE'])
{
	define('PATH_CORE', $_ENV['PATH_CORE']);
}
else
{
	define('PATH_CORE', PATH_ROOT . DS . 'core');
}

require_once PATH_CORE . DS . 'bootstrap' . DS . 'paths.php';

/*
|--------------------------------------------------------------------------
| Define CMS version
|--------------------------------------------------------------------------
|
| Pull in the version number. Although just a simple `define()` statement,
| we only want to define it in one place for the CMS and then have every
| application instance pull it in. Easier to maintain!
|
*/

require_once PATH_CORE . DS . 'bootstrap' . DS . 'version.php';

/*
|--------------------------------------------------------------------------
| Load The Framework
|--------------------------------------------------------------------------
|
| Here we will load the framework. We'll keep this is in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
*/

require_once PATH_CORE . DS . 'bootstrap' . DS . 'autoload.php';

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser.
|
*/

$app = require_once PATH_CORE . DS . 'bootstrap' . DS . 'start.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can simply call the run method,
| which will execute the request and send the response back to
| the client's browser.
|
*/

$app->run();
