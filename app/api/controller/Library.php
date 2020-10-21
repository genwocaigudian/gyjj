<?php


namespace app\api\controller;

use app\common\lib\Arr;
use app\common\lib\Show;
use app\common\services\News as NewsService;
use GuzzleHttp\Client;

class Library extends ApiBase
{
    public function index()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
            'query' => [
                'schoolid' => 23,
                'mark' => 4,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        return Show::success($body);
    }
    
    public function read()
    {
        $mid = input('param.mid', 0, 'intval');
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/major_view', [
            'query' => [
                'schoolid' => 23,
                'mid' => $mid,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        return Show::success($body);
    }
}
