<?php declare(strict_types=1);

namespace Banot\SDK\Api\Notifications;

use Banot\SDK\Api\HttpApiAsync;
use Banot\SDK\Exceptions\RuntimeException;
use Banot\SDK\Model\EmptyResponse;
use React\Promise\PromiseInterface;

class NotificationsAsync extends HttpApiAsync implements INotifications
{

	/**
	 * @return PromiseInterface<EmptyResponse, RuntimeException>
	 */
	public function notifyOneReport(string $email, string $name): PromiseInterface
	{
		return $this->post("/reports/{$email}/{$name}/notify", [], EmptyResponse::class);
	}

	/**
	 * @return PromiseInterface<EmptyResponse, RuntimeException>
	 */
	public function notifyAllWaitingReports(): PromiseInterface
	{
		return $this->post("/reports/notify", [], EmptyResponse::class);
	}
}
