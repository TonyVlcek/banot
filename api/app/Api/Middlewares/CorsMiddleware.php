<?php declare(strict_types = 1);

namespace App\Api\Middlewares;

use Contributte\Middlewares\IMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


final class CorsMiddleware implements IMiddleware
{

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
	{
		return $next(
			$request,
			$response->withHeader('Access-Control-Allow-Origin', '*')
		);
	}

}
