<?php
	namespace Xbin\Controller;
	
	use React\Http\Response;
	use React\EventLoop\LoopInterface;
	use Psr\Http\Message\ServerRequestInterface;	
	
	
	use Xbin\ChildProcessFactory;	
	
	class Download {
		private $childProcesses;
		
		public function __construct(ChildProcessFactory $childProcesses) {
			$this->childProcesses = $childProcesses;
		}

		public function __invoke(ServerRequestInterface $request, LoopInterface $loop) {
			$fileName = str_replace('download','uploads',trim($request->getUri()->getPath(),'/'));
			
			$readFile = $this->childProcesses->create("cat $fileName");
			$readFile->start($loop);
			
			return new Response(200,['Content-Disposition' => 'attachment'], $readFile->stdout);
		}
	}
	
	
?>