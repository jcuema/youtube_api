<?php


namespace App\Repository;


use App\Repository\Contracts\RepositoryInterface;
use Google_Client;
use Google_Service_YouTube;

class YoutubeRepository implements RepositoryInterface
{

    /**
     * @var string Youtube Key
     */
    protected $key;

    /**
     * @var Google_Client
     */
    protected $client;

    /**
     * @var int Max results
     */
    protected $maxRecords;

    /**
     * YoutubeRepository constructor.
     */
    public function __construct()
    {
        $this->key = env('YOUTUBE_API_KEY', '');
        $this->maxRecords = (int)env('MAX_RECORDS', '10');
        $this->client = new Google_Client();
        $this->client->setDeveloperKey($this->key);
    }

    /**
     * Performs search results in the youtube data api
     *
     * @param array $params
     * @return array
     * @throws \Google\Service\Exception
     */
    public function search(array $params): array
    {
        $youtube = new Google_Service_YouTube($this->client);

        $keyword = $params["search"];

        if (isset($params['max_results']) && !is_null($params["max_results"]) && is_numeric($params["max_results"])) {
            $this->maxRecords = (int)$params["max_results"];
        }

        $results = $youtube->search->listSearch('id, snippet', [
            'q' => $keyword,
            'maxResults' => $this->maxRecords,
        ]);

        return $this->getElements($results['items']);
    }

    /**
     * Create the object to the result
     *
     * @param $item
     * @return array
     */
    private function createElement($item): array
    {
        return [
            'published_at' => $item['snippet']['publishedAt'],
            'id' => $item['id']['videoId'],
            'title' => $item['snippet']['title'],
            'description' => $item['snippet']['description'],
            'thumbnail' => $item['snippet']['thumbnails']['default']['url'],
            'extra' => [
                'link' => "https://www.youtube.com/watch?v=" . $item['id']['videoId'],
            ],
        ];

    }

    /**
     * Return search results
     *
     * @param array $array
     * @return array
     */
    private function getElements(array $array): array
    {
        $items = [];

        foreach ($array as $item) {
            array_push($items, $this->createElement($item));
        }

        return $items;
    }
}
