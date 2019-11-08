<?php 

namespace App\Http\Traits;

trait ApiResponses 
{

	public function ResponseWithSuccess($payload)
	{
		return response()->json([
			'success' => true,
			'data' => $payload
		], 200);
	}

	public function ResponseUnauthorized()
	{
		return response()->json([
			'success' => false,
			'message' => 'Unauthorized'
		], 401);
	}

	public function ResponseWithError($error)
	{
		return response()->json([
			'success' => false,
			'message' => $error
		], 400);
	}
}