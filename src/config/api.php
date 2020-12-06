<?php declare(strict_types=1);

/**
 * @OA\OpenApi(
 *     @OA\Server(
 *         url="http://localhost/api/v1/",
 *         description="API server"
 *     ),
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Simple crud server",
 *         description="Crud example",
 *         @OA\Contact(name="nejtr0n <a6y@xakep.ru>"),
 *         @OA\License(name="MIT")
 *     ),
 *     @OA\Components(
 *        @OA\SecurityScheme(
 *           securityScheme="api_key",
 *           type="apiKey",
 *           in="header",
 *           name="auth"
 *        )
 *     ),
 * )
 */

/**
 * @OA\Schema(
 *     schema="ErrorModel",
 *     required={"status_code", "reason_phrase"},
 *     @OA\Property(
 *         property="status_code",
 *         type="integer",
 *         format="int32"
 *     ),
 *     @OA\Property(
 *         property="reason_phrase",
 *         type="string"
 *     )
 * )
 */