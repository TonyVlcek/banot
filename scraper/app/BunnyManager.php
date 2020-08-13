<?php


declare(strict_types=1);

namespace Banot\Scraper;

use Banot\Scraper\Exceptions\AckException;
use Banot\Scraper\Exceptions\ChannelUnavailableException;
use Banot\Scraper\Exceptions\ConnectionException;
use Banot\Scraper\Exceptions\NackException;
use Banot\Scraper\Utils\ReasonToString;
use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Protocol\MethodBasicConsumeOkFrame;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use Webmozart\Assert\Assert;

class BunnyManager
{
	public const DETAIL_PAGE_QUEUE = 'DPQ';
	public const LIST_PAGE_QUEUE = 'LPQ';

	private Client $client;

	private LoggerInterface $logger;

	private Channel $channel;


	public function __construct(LoopInterface $loop, LoggerInterface $logger, array $config)
	{
		$this->client = new Client($loop, $config, $logger);
		$this->logger = $logger;
	}

	/**
	 * @return PromiseInterface<Channel, ConnectionException>
	 */
	public function connect(int $prefetchCount = 0): PromiseInterface
	{
		return $this->client->connect()
			->then(
				fn(Client $client) => $client->channel(),
				function ($reason) {
					throw new ConnectionException("Connection Failed: " . ReasonToString::convert($reason));
				}
			)
			->then(fn(Channel $channel) => $this->channel = $channel)
			->then(
				function (Channel $channel) use ($prefetchCount) {
					return $channel->qos(0, $prefetchCount)->then(fn() => $channel);
				}
			);
	}

	/**
	 * Channel becomes available after successful connection (@throws ChannelUnavailableException
	 *
	 * @see BunnyManager::connect())
	 *
	 */
	public function getChannel(): Channel
	{
		if (!isset($this->channel)) //uninitialized
		{
			throw new ChannelUnavailableException(
				'The channel is not yet available. Are you connected to broker?'
			);
		}

		return $this->channel;
	}

	public function declareDPQ(): PromiseInterface
	{
		return $this->getChannel()->queueDeclare(self::DETAIL_PAGE_QUEUE, false, true);
	}

	public function declareLPQ(): PromiseInterface
	{
		return $this->getChannel()->queueDeclare(self::LIST_PAGE_QUEUE, false, true);
	}

	public function consumeDPQ($handler): PromiseInterface
	{
		return $this->consumeQueue($handler, self::DETAIL_PAGE_QUEUE);
	}

	public function consumeLPQ($handler): PromiseInterface
	{
		return $this->consumeQueue($handler, self::LIST_PAGE_QUEUE);
	}

	protected function consumeQueue($handler, string $queueName): PromiseInterface
	{
		Assert::true(is_callable($handler), 'Handler must be callable');

		return $this->getChannel()
			->consume($handler, $queueName)
			->then(
				function (MethodBasicConsumeOkFrame $response) use ($queueName) {
					$this->logger->info("{$queueName} declared. Waiting for messages. To exit press CTRL+C");

					return $response->consumerTag;
				}
			);
	}


	/**
	 * @return PromiseInterface<null, AckException>
	 */
	public function sendAck(\Bunny\Message $message): PromiseInterface
	{
		return $this->getChannel()->ack($message)->then(
			fn() => $this->logger->debug("[ACK] {$message->content}"),
			function ($reason) {
				$error = "ACK Failed: " . ReasonToString::convert($reason);
				$this->logger->error($error);
				throw new AckException($error);
			}
		);
	}

	/**
	 * @return PromiseInterface<null, NackException>
	 */
	public function sendNack(\Bunny\Message $message, bool $requeue = false): PromiseInterface
	{
		return $this->getChannel()->nack($message, false, $requeue)->then(
			fn() => $this->logger->debug("[NACK] {$message->content}"),
			function ($reason) {
				$error = "NACK Failed: " . ReasonToString::convert($reason);
				$this->logger->error($error);
				throw new NackException($error);
			}
		);
	}

	public function publishToLPQ()
	{
		throw new \LogicException('Not implemented.');
	}

	public function publishToDPQ()
	{
		throw new \LogicException('Not implemented.');
	}
}
