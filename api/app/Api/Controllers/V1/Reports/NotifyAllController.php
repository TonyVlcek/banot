<?php declare(strict_types = 1);

namespace App\Api\Controllers\V1\Reports;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Facades\NotificationFacade;
use Nette\Mail\SendException;

/**
 * @Path("/reports")
 * @Tag(name="reports")
 */
final class NotifyAllController extends BaseV1Controller
{
	/** @inject */
	public NotificationFacade $notificationFacade;


	/**
	 * @Path("/notify")
	 * @Method("POST")
	 */
	public function notifyAll(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		try {
			$reportsNotified = $this->notificationFacade->notifyAllWaitingReports();
		} catch (SendException $e) {
			throw  ServerErrorException::create()
				->withCode(ApiResponse::S500_INTERNAL_SERVER_ERROR)
				->withMessage("Notification message(s) failed to send.")
				->withPrevious($e);
		}

		if ($reportsNotified === 0) {
			return $response->withStatus(ApiResponse::S204_NO_CONTENT);
		} else {
			return $response->withStatus(ApiResponse::S200_OK)
				->writeJsonBody(['reportsNotified' => $reportsNotified]);
		}
	}
}
