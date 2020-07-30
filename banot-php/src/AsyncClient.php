<?php declare(strict_types=1);

namespace Banot\SDK;

use Banot\SDK\Api\Items;
use Banot\SDK\Api\Notifications;
use Banot\SDK\Api\Reports;
use Banot\SDK\Api\WebResources;
use Banot\SDK\Hydrator\IHydrator;
use Banot\SDK\Hydrator\ModelHydrator;
use Clue\React\Buzz\Browser;
use React\EventLoop\LoopInterface;


class AsyncClient
{

	private LoopInterface $loop;

	private Browser $client;

	private IHydrator $hydrator;

	private Items\ItemsAsync $items;

	private Notifications\NotificationsAsync $notifications;

	private Reports\ReportsAsync $reports;

	private WebResources\WebResourcesAsync $resources;


	public function __construct(LoopInterface $loop, string $url, string $apiKey)
	{
		$this->loop = $loop;

		$this->client = (new Browser($loop))
			->withBase($url)
			->withOptions([
				'timeout' => 15,
				'header' => [
					'User-Agent' => 'banot/banot-sdk-php',
					'Authorization' => 'Basic ' . base64_encode("api:{$apiKey}"),
				]
			]);

		$this->hydrator = new ModelHydrator();
	}

	public function items(): Items\ItemsAsync
	{
		if (!isset($this->items)) {
			return $this->items = new Items\ItemsAsync($this->client, $this->hydrator, $this->loop);
		}

		return $this->items;
	}

	public function notifications(): Notifications\NotificationsAsync
	{
		if (!isset($this->notifications)) {
			return $this->notifications = new Notifications\NotificationsAsync($this->client, $this->hydrator, $this->loop);
		}

		return $this->notifications;
	}

	public function reports(): Reports\ReportsAsync
	{
		if (!isset($this->reports)) {
			return $this->reports = new Reports\ReportsAsync($this->client, $this->hydrator, $this->loop);
		}

		return $this->reports;
	}

	public function resources(): WebResources\WebResourcesAsync
	{
		if (!isset($this->resources)) {
			return $this->resources = new WebResources\WebResourcesAsync($this->client, $this->hydrator, $this->loop);
		}

		return $this->resources;
	}

}
