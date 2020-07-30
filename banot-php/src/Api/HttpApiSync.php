<?php declare(strict_types=1);

namespace Banot\SDK\Api;


use React\EventLoop\LoopInterface;

abstract class HttpApiSync
{

	protected HttpApiAsync $async;

	protected LoopInterface $loop;


	public function __construct(HttpApiAsync $async, LoopInterface $loop)
	{
		$this->async = $async;
		$this->loop = $loop;
	}

}
