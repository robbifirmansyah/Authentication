<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     description="Contoh API doc menggunakan OpenAPI/Swagger",
 *     version="0.0.1",
 *     title="Contoh API documentation",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="choirudin.emchagmail.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class GreetController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/greet",
     *     tags={"Greeting"},
     *     summary="Returns a greeting message",
     *     description="API untuk memberikan pesan sapaan berdasarkan nama depan dan nama belakang",
     *     operationId="greet",
     *     @OA\Parameter(
     *         name="firstname",
     *         description="Nama depan pengguna",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="lastname",
     *         description="Nama belakang pengguna",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Halo John Doe",
     *                 "success": true,
     *                 "data": {
     *                     "firstname": "John",
     *                     "lastname": "Doe"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing data",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Missing data",
     *                 "success": false
     *             }
     *         )
     *     )
     * )
     */
    public function greet(Request $request)
    {
        $userData = $request->only([
            'firstname',
            'lastname',
        ]);

        if (empty($userData['firstname']) && empty($userData['lastname'])) {
            return response()->json([
                'message' => 'Missing data',
                'success' => false
            ], 400);
        }

        return response()->json([
            'message' => 'Halo ' . $userData['firstname'] . ' ' . $userData['lastname'],
            'success' => true,
            'data' => [
                'firstname' => $userData['firstname'],
                'lastname' => $userData['lastname']
            ]
        ], 200);
    }
}
