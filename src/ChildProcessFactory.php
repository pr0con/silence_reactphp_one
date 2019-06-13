<?php
	namespace Xbin;

	use React\ChildProcess\Process;

	class ChildProcessFactory {
		private $currentWorkingDirectory;
		
		public function __construct($currentWorkingDirectory) {
			$this->currentWorkingDirectory = $currentWorkingDirectory;
		}
		
		public function create($command) {
			return new Process($command, $this->currentWorkingDirectory);
		}
	}	

	
?>