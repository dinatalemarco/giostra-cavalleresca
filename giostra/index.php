<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @author Ciavarro Cristina <cristina.ciavarro@gmail.com>
 *
 */


 
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}


require_once(dirname(__FILE__) .'/includes/uuoden.inc.php');	
//require_once(dirname(__FILE__) .'/modules/Giostra/install.php');
require_once(dirname(__FILE__) .'/business/impl/UsersManagementImpl.php');	
require_once(dirname(__FILE__) .'/business/impl/BorghiManagementImpl.php');	
require_once(dirname(__FILE__) .'/business/impl/PaliiManagementImpl.php');	
require_once(dirname(__FILE__) .'/business/impl/InscriptionsManagementImpl.php');	
require_once(dirname(__FILE__) .'/business/impl/EventsManagementImpl.php');
require_once(dirname(__FILE__) .'/business/impl/RolesManagementImpl.php');
require_once(dirname(__FILE__) .'/business/impl/ReservationsManagementImpl.php');


session_start();

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/src/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/src/routes.php';
$routes($app);

// Run app
$app->run();
