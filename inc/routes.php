<?php

use Xbin\ChildProcessFactory;
use Xbin\Controller\Index;
use Xbin\Controller\Upload;
use Xbin\Controller\FetchImage;
use Xbin\Controller\Download;
use Xbin\Controller\FavIcon;

use React\Http\Response;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use Psr\Http\Message\ServerRequestInterface;


$childProcessFactory = new ChildProcessFactory(dirname(__DIR__, 1));

return [
	'/' => new Index($childProcessFactory),
	'/upload' => new Upload($childProcessFactory),	
	'/uploads/.*\.(jpg|png)$' => new FetchImage($childProcessFactory),
	'/thumbnails/.*\.(jpg|png)$' => new FetchImage($childProcessFactory),
	'/download/.*\.(jpg|png)$' => new Download($childProcessFactory),
	'/favicon.ico' => new FavIcon()
]	
?>