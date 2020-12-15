<?php

namespace App\Http\Controllers;

use App\Repository\YoutubeRepository;
use Illuminate\Http\Request;

class ApiYoutubeController extends Controller
{

    /**
     * @param YoutubeRepository $repository
     * @param Request $request
     */
    public function getSearch(YoutubeRepository $repository, Request $request)
    {
        $params = $request->all();

        if (!in_array('search', $this->keys($params))) {
            return response()->json(['message' => 'param search not found'], 422);
        }

        if (!in_array('max_results', $this->keys($params))) {
            $params['max_results'] = null;
        }

        if (empty($params['search'])) {
            return response()->json(['message' => 'Search parameter is empty, please enter a value.'], 422);
        }

        try {
            $results = $repository->search($params);

            if(count($results) > 0){
                return $results;
            }else{
                return response()->json(['message' => 'No results found for the given keyword. Try another keyword.'], 404);
            }
        } catch (\Google\Service\Exception $e) {
            $error = json_decode($e->getMessage())->error;
            return response()->json([
                'message' => "Error retrieving information from youtube videos. The service returned the following message: {$error->message}"
            ], 400);
        }

    }

    /**
     * Returns the keys of array
     *
     * @param array $array
     * @return array
     */
    private function keys(array $array): array
    {
        return array_keys($array);
    }
}
