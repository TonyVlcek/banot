<?php declare(strict_types=1);

namespace Banot\SDK\Api\WebResources;

use Banot\SDK\Api\HttpApiAsync;
use Banot\SDK\Exceptions\RuntimeException;
use Banot\SDK\Model\WebResources\WebResource;
use React\Promise\PromiseInterface;

class WebResourcesAsync extends HttpApiAsync implements IWebResources
{

	/**
	 * @return PromiseInterface<WebResource, RuntimeException>
	 */
	public function getByName(string $name): PromiseInterface
	{
		return $this->get("/resources/{$name}", [], WebResource::class);
	}

}
