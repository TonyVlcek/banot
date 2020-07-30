<?php declare(strict_types=1);

namespace Banot\SDK;

use Banot\SDK\Api\Items;
use Banot\SDK\Api\Notifications;
use Banot\SDK\Api\Reports;
use Banot\SDK\Api\WebResources;
use Banot\SDK\Hydrator\IHydrator;
use Banot\SDK\Hydrator\ModelHydrator;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;


class Client
{

	private AsyncClient $client;

	private IHydrator $hydrator;

	private LoopInterface $loop;

	private Items\ItemsSync $items;

	private Notifications\NotificationsSync $notifications;

	private Reports\ReportsSync $reports;

	private WebResources\WebResourcesSync $resources;


	public function __construct(string $url, string $apiKey)
	{
		$this->loop = Factory::create();
		$this->client = new AsyncClient($this->loop, $url, $apiKey);
		$this->hydrator = new ModelHydrator();

		$this->loop->run();
	}

	public function items(): Items\ItemsSync
	{
		if (!isset($this->items)) {
			return $this->items = new Items\ItemsSync($this->client->items(), $this->loop);
		}

		return $this->items;
	}

	public function notifications(): Notifications\NotificationsSync
	{
		if (!isset($this->notifications)) {
			return $this->notifications = new Notifications\NotificationsSync($this->client->notifications(), $this->loop);
		}

		return $this->notifications;
	}

	public function reports(): Reports\ReportsSync
	{
		if (!isset($this->reports)) {
			return $this->reports = new Reports\ReportsSync($this->client->reports(), $this->loop);
		}

		return $this->reports;
	}

	public function resources(): WebResources\WebResourcesSync
	{
		if (!isset($this->resources)) {
			return $this->resources = new WebResources\WebResourcesSync($this->client->resources(), $this->loop);
		}

		return $this->resources;
	}

}
