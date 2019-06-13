<?php
	namespace Xbin;
	
	use React\Http\Response;
	use React\EventLoop\LoopInterface;
	use Psr\Http\Message\ServerRequestInterface;
	
	class Router {
		private $routes = [];
		
		private $loop;
		public function __construct(LoopInterface $loop) {
			$this->loop = $loop;
		}
		
		public function __invoke(ServerRequestInterface $request) {
			$path = trim($request->getUri()->getPath());

			echo "=========================\n";
			echo "Route: ".$path.PHP_EOL;
			foreach($this->routes as $pattern => $handler) {
				
				echo "CHECKING ".$pattern; echo "     @CLASS_OBJECT | ".get_class($handler).PHP_EOL;
				
				
				if(preg_match('@^'.$pattern.'$@', $path)) {
					return $handler($request, $this->loop);
				}
			}
			echo "=========================\n";
			
			echo 'no match'.PHP_EOL;
			return $this->notFound($path);
			
			
			//$handler = $this->routes[$path] ?? $this->notFound($path);
			//return $handler($request, $this->loop);			
		}
			
		public function add($path, callable $handler) {
			$this->routes[$path] = $handler;
		}
				
		public function load($filename) {
			$routes = require $filename;
			foreach($routes as $path => $handler) {
				$this->add($path, $handler);
			}
		}
		

		private function notFound($path) {
			return new Response(404, ['Content-Type' => 'text/html'], "No request handler found for $path");
		}
	}	
?>