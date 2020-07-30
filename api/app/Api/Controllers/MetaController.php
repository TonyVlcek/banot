<?php declare(strict_types=1);

namespace App\Api\Controllers;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\OpenApi\ISchemaBuilder;

/**
 * @Path("/meta")
 * @Tag(name="meta")
 */
final class MetaController extends BaseController
{
	private ISchemaBuilder $schemaBuilder;

	public function __construct(ISchemaBuilder $schemaBuilder)
	{
		$this->schemaBuilder = $schemaBuilder;
	}

	/**
	 * @Path("/schema")
	 * @Method("GET")
	 */
	public function index(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$openApi = $this->schemaBuilder->build();

		return $response->writeJsonBody($openApi->toArray());
	}

}
