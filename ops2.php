<?php
//https://gist.github.com/Mevrael/6855dd47d45fa34ee7161c8e0d2d0e88 LARAVEL RELATED FOR LATER...

//composer require react/event-loop
//composer require react/child-process

//React PHP comes with built in promises reactphp/promise part of the the http package....
//use React\Promise\Deferred

//https://reactphp.org/
//https://sergeyzhuk.me/reactphp-series

//https://www.php-fig.org/


/* NOTE React deferred can return a promise wich resolved withan instance of response */
/* NOTE throw errors like>   throw new \Exception('some error'); */


//OOP NOTES
//use static methods to produce global extended class values to children:: like how many connections on parent class server...
//static methods or variables cannont be changed by children
//use self::something to set in class methods...
//Errors and Exceptions are children of throwable...
//use set_error_handler to set what to do with certain errors
//cutum_error_funcname(err_level,msg,file(optional),line(optional),context(optional))
//extend the exception class, google it...fancy for custom error handler...
//to mimic composer use spl_autoload_register && loader function name...


//SUPERVISOR :: scripts as service...
//apt-get install supervisor
//configuration files stored in @> /etc/supervisor/conf.d/
//after conf created> 
//supervisorctl reread
//supervisorctl update
//supervisorctl reload
//supervisorctl start reactphp-server
//supervisor status
//master log @ /var/log/supervisord.log

//last probably need absolute path for error page...

require_once 'vendor/autoload.php';


use React\Http\Server;
use React\Http\response;
use Psr\Http\Message\ServerRequestInterface;


use Xbin\Router;
use Xbin\ErrorHandler;

$loop = React\EventLoop\Factory::create();
$router = new Router($loop);
$router->load(__DIR__.'/inc/routes.php');

//add catch all errors here
$errorHandler = new ErrorHandler($loop);
$server = new Server(function(ServerRequestInterface $request) use ($router, $errorHandler) {
	try {
		return $router($request);
	} catch(Throwable $exception) {
		return $errorHandler->handle($exception);
	}
});

/*
$server->on('error', function (Throwable $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
});
*/
$server->on('error', function(Exception $exception) {
	echo 'Error: ' . $exception->getMessage() . PHP_EOL;
	if($exception->getPrevious() !== null) {
		echo 'Error:'.$exception->getPrevious()->getMessage().PHP_EOL;
	}
});

$socket = new React\Socket\Server('0.0.0.0:9000',$loop);
$server->listen($socket);

$loop->run();
?>