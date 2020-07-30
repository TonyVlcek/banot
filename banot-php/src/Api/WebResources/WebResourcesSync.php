<?php


declare(strict_types=1);

namespace Banot\SDK\Api\WebResources;

use Banot\SDK\Api\HttpApiSync;
use function Clue\React\Block\await;

/**
 * @property WebResourcesAsync $async
 */
class WebResourcesSync extends HttpApiSync implements IWebResources
{

	public function getByName(string $name)
	{
		return await($this->async->getByName($name), $this->loop);
	}

}
