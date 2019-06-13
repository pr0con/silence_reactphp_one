<?php
	namespace Xbin\Controller;
	
	use React\Http\Response;
	use React\ChildProcess\Process;
	use React\EventLoop\LoopInterface;
	use Psr\Http\Message\ServerRequestInterface;

	
	use Xbin\ChildProcessFactory;	

	class Index {
		private $childProcesses;
		
		public function __construct(ChildProcessFactory $childProcesses) {
			$this->childProcesses = $childProcesses;
		}
		
		public function __invoke(ServerRequestInterface $request, LoopInterface $loop) {
			//$listFiles = new Process('ls /var/www/php_systems/ops2/uploads');
			//$listFiles = new Process('ls uploads', dirname(__DIR__, 2));
			
			
			$listFiles = $this->childProcesses->create('ls uploads');
			$listFiles->start($loop);	
			
			$renderPage = $this->childProcesses->create('php pages/index.php');
			$renderPage->start($loop);
			
			$listFiles->stdout->pipe($renderPage->stdin);		
			
			return new Response(200, ['Content-Type' => 'text/html'], $renderPage->stdout);			
		}
	}
?>