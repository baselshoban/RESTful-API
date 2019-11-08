<?php 

namespace App\Http\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponses 
{

	public function ResponseWithSuccess($payload)
	{
		return response()->json([
			'success' => true,
			'status' => Response::HTTP_OK,
			'data' => $payload
		], Response::HTTP_OK);
	}

	public function ResponseUnauthorized()
	{
		return response()->json([
			'success' => false,
			'status' => Response::HTTP_UNAUTHORIZED,
			'message' => 'Unauthorized'
		], Response::HTTP_UNAUTHORIZED);
	}

	public function ResponseWithError($error)
	{
		return response()->json([
			'success' => false,
			'status' => Response::HTTP_BAD_REQUEST,
			'message' => $error,
		], Response::HTTP_BAD_REQUEST);
	}

	public function ResponseMethodNotAllowed()
	{
		return response()->json([
			'success' => false,
			'status' => Response::HTTP_METHOD_NOT_ALLOWED,
			'message' => 'The used HTTP method is not allowed for the requested path.',
		], Response::HTTP_METHOD_NOT_ALLOWED);
	}

	public function ResponseNotFound()
	{
		return response()->json([
			'success' => false,
			'status' => Response::HTTP_NOT_FOUND,
			'message' => 'The requested resource could not be found!',
		], Response::HTTP_NOT_FOUND);
	}

	public function ResponseInternalError()
	{
		return response()->json([
			'success' => false,
			'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
			'message' => 'Internal server error occurred. Please contact with us.',
		], Response::HTTP_INTERNAL_SERVER_ERROR);
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