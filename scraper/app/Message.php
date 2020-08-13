<?php

declare(strict_types=1);

namespace Banot\Scraper;


class Message extends \Bunny\Message
{

	private const VALUE_SEPARATOR = '|';

	private string $resource;

	private string $url;

	public function __construct($consumerTag, $deliveryTag, $redelivered, $exchange, $routingKey, array $headers, $content)
	{
		parent::__construct(
			$consumerTag, $deliveryTag, $redelivered, $exchange, $routingKey, $headers, $content
		);

		[$resource, $url] = explode(self::VALUE_SEPARATOR, $content);

		$this->resource = $resource;
		$this->url = $url;
	}

	public static function from(\Bunny\Message $bunnyMessage): self
	{
		return new Message(
			$bunnyMessage->consumerTag,
			$bunnyMessage->deliveryTag,
			$bunnyMessage->redelivered,
			$bunnyMessage->exchange,
			$bunnyMessage->routingKey,
			$bunnyMessage->headers,
			$bunnyMessage->content
		);
	}

	public function getResource(): string
	{
		return $this->resource;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function __toString(): string
	{
		return implode(self::VALUE_SEPARATOR, [$this->resource, $this->url]);
	}
}
