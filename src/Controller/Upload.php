<?php
	namespace Xbin\Controller;
	
	use React\Http\Response;
	use React\ChildProcess\Process;
	use React\EventLoop\LoopInterface;
	use Psr\Http\Message\ServerRequestInterface;

    use React\Promise\Deferred;
    
	use Xbin\ChildProcessFactory;	
	
	class Upload {
		private $childProcesses;
		
		public function __construct(ChildProcessFactory $childProcesses) {
			$this->childProcesses = $childProcesses;
		}		
		
		
		public function __invoke(ServerRequestInterface $request, LoopInterface $loop) {
			
			$formData = $request->getParsedBody();
			print_r($formData); //$someFormField = $request->getParsedBody()['form_field_here'];
			
			$files = $request->getUploadedFiles();
			
			$file 	  = $request->getUploadedFiles()['form_file'];
			$fileName =  str_replace(' ', '_', $file->getClientFilename());
				
			$saveUpload = $this->childProcesses->create("cat > uploads/{$fileName}");
			$saveUpload->start($loop);
			
			$saveUpload->stdin->end($file->getStream()->getContents());
			
			
			$deferred = new Deferred();
			
			$saveUpload->stdin->on('close', function() use ($fileName, $loop) {
				$this->createThumbnail($fileName, $loop);
			});
			return $deferred->promise();
			
		}
		
		private function createThumbnail($fileName, LoopInterface $loop) {
			$createThumbnail = $this->childProcesses->create("convert uploads/$fileName -resize 128x128 thumbnails/$fileName");	
			$createThumbnail->start($loop);
			$createThumbnail->on('exit', function() use ($deferred) {
				//return new Response(302, ['Location' => '/']);
				//once server has finished everything resolve promise to a response...
				$deferred->resolve(new Response(302, ['Location' => '/']));
			});
		}
	}
?>