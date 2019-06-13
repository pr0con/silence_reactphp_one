<?php
	namespace Xbin\Controller;
	
	use React\Http\Response;
	use React\ChildProcess\Process;
	use React\EventLoop\LoopInterface;
	use Psr\Http\Message\ServerRequestInterface;
	
	use Xbin\ChildProcessFactory;	
	
	class FetchImage {
		private $childProcesses;
		
		public function __construct(ChildProcessFactory $childProcesses) {
			$this->childProcesses = $childProcesses;
		}	
		
		public function __invoke(ServerRequestInterface $request, LoopInterface $loop) {
			$fileName = trim($request->getUri()->getPath(), '/');
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);
			
			echo "Looking For: ".$fileName." \n";
				
			//filename includes uploads/ or thumbnails/ from src on image tag...
			$readFile = new Process("cat /var/www/php_systems/ops2/$fileName");
			$readFile->start($loop);
			
			return new Response(200, ['Content-Type' => "image/$ext"], $readFile->stdout);
		}
	}
?>