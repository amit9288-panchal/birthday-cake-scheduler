<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\CakeEventResource;
use App\Http\Traits\ResponseTrait;
use App\Models\CakeEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Carbon\Carbon;
use OpenApi\Attributes as OA;

/**
 * @OA\Info(title="Cake Scheduler API Documentation", version="1.0.0")
 */
class CakeEventController extends Controller
{
    use ResponseTrait;

    #[OA\Get(
        path: "/api/v1/cake/days",
        summary: "Get cake days",
        tags: ['CakeEvent'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean'),
                        new OA\Property(property: 'errorCode', type: 'string'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'data', type: 'array', items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'cake_date', type: 'date-time'),
                                new OA\Property(property: 'small_cakes', type: 'integer'),
                                new OA\Property(property: 'large_cakes', format: 'integer'),
                                new OA\Property(
                                    property: 'developers', type: 'array', items: new OA\Items(
                                    properties: []
                                )
                                ),
                                new OA\Property(property: 'created_at', type: 'date-time'),
                            ]
                        )
                        ),
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function getCakeDays(Request $request): JsonResponse
    {
        try {
            $upcomingCakeEvent = CakeEvent::where('cake_date', '>=', Carbon::now())->get();
            return $this->successResponse(new CakeEventResource($upcomingCakeEvent));
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
