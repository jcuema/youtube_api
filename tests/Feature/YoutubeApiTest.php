<?php

namespace Tests\Feature;

use http\Env\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertCount;

class YoutubeApiTest extends TestCase
{

    protected $keyword = 'Muse';

    public function testShouldShowError422WhenNotSendingSearchParameter()
    {
        $response = $this->call('GET', '/api/youtube', []);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    public function testShouldShowError422WhenSendingEmptySearchParameter()
    {
        $response = $this->call('GET', '/api/youtube', [
            'search' => ''
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    public function testShouldReturn10ResultsByDefault()
    {
        $response = $this->call('GET', '/api/youtube', [
            'search' => $this->keyword
        ]);

        print_r($response->json());

        $response->assertStatus(200);

        $this->assertCount(10, $response->json());
    }

    public function testMustReturnTheNumberOfResultsReportedInTheMaxResultsParameterWhenItIsPassed()
    {
        $maxResults = 7;

        $response = $this->call('GET', '/api/youtube', [
            'search' => $this->keyword,
            'max_results' => $maxResults,
        ]);

        $response->assertStatus(200);

        $this->assertCount($maxResults, $response->json());
    }

    public function testJsonStructure()
    {
        $response = $this->call('GET', '/api/youtube', [
            'search' => $this->keyword
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'published_at',
                'id',
                'title',
                'description',
                'thumbnail',
                'extra' => [
                    'link'
                ]
            ]
        ]);
    }

}
