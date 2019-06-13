<?php
	namespace Xbin;
	
	use Throwable;
	use React\Http\Response;
	use React\ChildProcess\Process;
	use React\EventLoop\LoopInterface;

	
	class ErrorHandler {
		private $loop;
		
		public function __construct(LoopInterface $loop) {
			$this->loop = $loop;
		}
		
		public function handle(Throwable $throwable) {
			$this->report($throwable);
			return $this->process($throwable);
		}
		
		private function report(Throwable $throwable) {
			echo 'Error: '.$throwable->getMessage(). PHP_EOL;
		}
		
		private function process(Throwable $throwable) {
			$process = new Process('cat pages/error.php');
			$process->start($this->loop);
			
			return new Response(500, ['Content-Type' => 'text/html'], $process->stdout);
		}
	}
?>