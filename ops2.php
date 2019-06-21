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
//use static methods to produce global extended class values to children:: accessable without creating an object using double ::
//like how many connections on parent class server...
//static methods or variables cannont be changed by children
//use self::something to set in class methods...
//Errors and Exceptions are children of throwable...
//use set_error_handler to set what to do with certain errors
//cutum_error_funcname(err_level,msg,file(optional),line(optional),context(optional))
//extend the exception class, google it...fancy for custom error handler...
//to mimic composer use spl_autoload_register && loader function name...


#SELF :: use to access static properties, methods, and constants of a class itself. 
/*
	-self::$staticProperty
	-self::staticMethod()
	-self::CONSTANTNAME
*/

#OBJECT SERIALIZATION
/*
	$s = serialize($obj);
	$s = unserialize($obj);
	
	public function __sleep() {
	}
	public function __wakeup() {
	}	
*/

#OBJECT CLONING
/*
	$someObj = clone $clondeObj; //this is shallow copy
	
	//deep cloning :: add magic method to class :: when obj is cloned it creates a completly not shallow seperate obj.
	public $thisObj;
	public function __clone() { $this->thisObj = clone $this->thisOjb; }
	public function __construct($cloningObj) { $this->thisObj = $cloningObj; }
	
	//recursive cloning :: use a TRAIT :: add -> use cloneAble to beginning of class if needed
	trait cloneAble {
		public function __clone() {
			foreach($this as $key=>$value) {
				if(is_object($value)) {
					$this->$key = clone $this->$key;
				}
			}
		}
	}
	
	//double linking problem :: cloned objects link to same other object...
	//solve with serialize and unserialize
*/

/*
#TYPE HINTING :: use this in front of function parmeters when defining function,, php will throw error explaining what type is expected.
#Scalar types
	-bool
	-int
	-float
	-string
#Non Scalar Types
	-class/interface
	-self
	-array
	-callable
*/
#basically telling what expect in function variables so error reporting is cleaner
# type hint return types as well...
/*
	function() :int {
	}
*/

#Overloading :: using magic methods
/*
	__set() called to set overloaded property
	__get() called to read overloaded property
	__isset() check if overloaded prop is set
	__unset() unset overloaded property
	
	private $_xtraProps = array();
	public function __set($propName,propValue) {
		#allows basically custom props in classes
		$this->xtraProps[$propName] = $propValue;
	}
	
	public function __get($propName) {
		if(array_key_exists($propName, $this->_xtraProps)) {
			return $this->_xtraProps[$propName];
		}else {
			return null;
		}
	}
	
	public function __isset($propName) {
		if(isset($this->_xtraProps[$propName])) {
			echo "Property \$$proName is set.";
		} else {
			echo "Property \$$propName is not set.";
		}
	}	
*/


#Method Overloading :: multiple methods same name different args...
/*
	__call and && callStatic basically do stuff with random non existant methods on object...
*/


#TRAITS
/*
	Multiple Inheritance's basically:
	trait someTrait {
		public function doSomething() {
		}
		
		abstract public function mustUse();
	}	
	
	class bla extends nothing {
		use someTrait;
		
		public function mustUse() {
			//im abstract.
		}
	}
	
	#instead of allow picking a method over another conflicting just dont have conflicting methods
	#in traits
	
	***Traits can use other traits*** USEFULL
	
	#classes cant define same property of used state.

*/




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