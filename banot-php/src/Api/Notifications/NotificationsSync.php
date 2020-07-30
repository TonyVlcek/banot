<?php


declare(strict_types=1);

namespace Banot\SDK\Api\Notifications;

use Banot\SDK\Api\HttpApiSync;
use Banot\SDK\Model\EmptyResponse;
use function Clue\React\Block\await;

/**
 * @property NotificationsAsync $async
 */
class NotificationsSync extends HttpApiSync implements INotifications
{

	public function notifyOneReport(string $email, string $name): EmptyResponse
	{
		return await($this->async->notifyOneReport($email, $name), $this->loop);
	}

	public function notifyAllWaitingReports(): EmptyResponse
	{
		return await($this->async->notifyAllWaitingReports(), $this->loop);
	}
}
