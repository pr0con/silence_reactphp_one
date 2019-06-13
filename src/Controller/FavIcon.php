<?php
	namespace Xbin\Controller;
	
	use React\Http\Response;
	use React\EventLoop\LoopInterface;
	use Psr\Http\Message\ServerRequestInterface;
	
	class FavIcon {
		public function __invoke(ServerRequestInterface $request) {
			return new Response(200, ['Content-Type' => 'image/x-icon']);
		}		
	}
?>