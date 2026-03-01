<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\MovieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Search movies
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            's' => 'required|string|min:1',
            'page' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('movie.validation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }

        $query = $request->input('s');
        $page = $request->input('page', 1);

        $result = $this->movieService->searchMovies($query, $page);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => __('movie.search_success'),
                'data' => $result['data'],
                'total_results' => $result['total_results'],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'data' => [],
        ], 404);
    }

    /**
     * Get movie details by IMDb ID
     *
     * @param string $imdbId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($imdbId)
    {
        if (empty($imdbId)) {
            return response()->json([
                'success' => false,
                'message' => __('movie.id_required'),
            ], 422);
        }

        $result = $this->movieService->getMovieDetails($imdbId);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => __('movie.detail_success'),
                'data' => $result['data'],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 404);
    }
}
