<?php declare(strict_types = 1);

namespace App\Api\Controllers\V1\Reports;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestBody;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Requests\ReportRequest;
use App\Api\Responses\ReportDetailRes;
use App\Facades\ReportFacade;
use InvalidArgumentException;
use Nette\Mail\SendException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Path("/reports")
 * @Tag(name="reports")
 */
final class CreateReportController extends BaseV1Controller
{
	/** @inject */
	public ReportFacade $reportFacade;

	/** @inject */
	public SerializerInterface $serializer;


	/**
	 * @Path("/")
	 * @Method("POST")
	 * @RequestBody(entity="App\Api\Requests\ReportRequest")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ReportDetailRes
	{
		/** @var ReportRequest $report */
		$report = $request->getEntity();

		try {
			$report = $this->reportFacade->createReport(
				$report->email,
				$report->query,
				$report->frequency,
				$report->created,
			);

			return ReportDetailRes::fromOrmEntity($report);
		} catch (SendException $e) {
			throw ServerErrorException::create()
				->withCode(ApiResponse::S500_INTERNAL_SERVER_ERROR)
				->withMessage('Sending of the confirmation notification has failed.');
		} catch (InvalidArgumentException $e) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S422_UNPROCESSABLE_ENTITY)
				->withMessage($e->getMessage());
		}
	}
}
