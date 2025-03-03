<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\CakeEventResource;
use App\Http\Traits\ResponseTrait;
use App\Models\CakeEvent;
use App\Models\Developer;
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


    #[OA\Post(
        path: "/api/v1/birthday/upload",
        summary: "Upload developer's birthday list",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: 'birthday_file',
                            format: 'file'
                        ),
                    ]
                )
            ),
        ),
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
                            property: 'data', type: 'array', items: new OA\Items(properties: [])
                        ),
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function uploadDeveloperBirthDay(Request $request): JsonResponse
    {
        try {
            $file = $request->file('birthday_file', null);

            if (!$file) {
                return $this->errorResponse("Please upload a file", 422);
            }
            $content = file($file->getRealPath());
            foreach ($content as $line) {
                [$name, $birth_date] = explode(',', trim($line));
                Developer::updateOrCreate([
                    'name' => $name,
                    'birth_date' => $birth_date,
                ]);
            }
            return $this->successResponse([], "Birthday file data successfully stored");
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
