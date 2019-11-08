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

	public function paginate($dataCollection)
	{
		$limit = 10;
		if ( request()->limit && request()->limit <= 100)
			$limit = request()->limit;

		$pagination = $dataCollection
			->paginate($limit);

		return [
			"records" => $pagination->items(),
			"pagination" => [
				"current_page" => $pagination->currentPage(),
				"total_pages" => ceil($pagination->total() / $pagination->perPage()),
				"total_count" => $pagination->count(),
				"limit" => $pagination->perPage(),
			]
		];
	}
}