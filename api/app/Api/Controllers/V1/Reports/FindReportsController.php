<?php declare(strict_types = 1);

namespace App\Api\Controllers\V1\Reports;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Responses\CollectionResponse;
use App\Api\Responses\ReportDetailRes;
use App\Facades\ReportFacade;

/**
 * @Path("/reports")
 * @Tag(name="reports")
 */
final class FindReportsController extends BaseV1Controller
{
	/** @var ReportFacade @inject */
	public $reportFacade;


	/**
	 * @Path("/{email}")
	 * @Method("GET")
	 * @RequestParameters({
	 *      @RequestParameter(name="email", type="string"),
	 * })
	 */
	public function findByEmail(ApiRequest $request, ApiResponse $response): CollectionResponse
	{
		$reports = $this->reportFacade->findByEmail($request->getParameter('email'));

		return CollectionResponse::fromOrmCollection($reports, ReportDetailRes::class);
	}
}
