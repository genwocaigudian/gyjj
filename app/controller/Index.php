<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;
use app\common\services\News;
use GuzzleHttp\Client;

class Index extends BaseController
{
    public function index()
    {
        return Show::success();
    }

    public function test()
    {
        $res = (new News())->rssSync(2);
        return Show::success([$res]);
    }

    public function sync()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
            'query' => [
                'schoolid' => 23,
                'mark' => 1,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);

        if ($body['code']) {
            $temp = [
                'title' => '学校简介',
                'cate_id' => 1,
                'img_urls' => json_encode([""]),
                'pub_date' => time(),
                'user_id' => 1,
                'content' => $body['data'],
                'create_time' => time(),
                'update_time' => time()
            ];
//            (new News())->insertData($temp);
//            return Show::success(['data' => $body['data']]);
        }
        return Show::success();
    }

    public function sync1()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
            'query' => [
                'schoolid' => 23,
                'mark' => 6,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        $data = [];
        foreach ($body['mark20'] as $value) {
//            $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/view', [
//                'query' => [
//                    'schoolid' => 23,
//                    'mark' => $value['id'],
//                ]
//            ]);
            $response = curl_get("https://highschool.schoolpi.net/api/vocational_lists/view?schoolid=23&id={$value['id']}");

            $body = json_decode($response, true);
            $temp = [
                'title' => $value['title'],
                'cate_id' => 17,
                'img_urls' => json_encode($value['thumb']),
                'pub_date' => strtotime($value['create_time']),
                'user_id' => 1,
                'content' => "",
                'create_time' => time(),
                'update_time' => time(),
                'read_count' => $value['hits']??0,
            ];
            $temp['content'] = $body['code'] == 1 ? $body['data']['content'] : "";
            array_push($data, $temp);
//            (new News())->insertData($temp);
//            return Show::success(['data' => $body['data']]);
        }

//        if ($body['code'] == 1) {
//            $temp['content'] = $body['data']['content'];
//            halt($temp);
////            (new News())->insertData($temp);
////            return Show::success(['data' => $body['data']]);
//        }
        return Show::success($data);

    }

    public function sync2()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/index', [
            'query' => [
                'schoolid' => 23,
                'mark' => 39,
            ]
        ]);
        $body = json_decode($response->getBody()->getContents(), true);
        $data = [];
        foreach ($body['data'] as $value) {
//            $response = $client->request('GET', 'https://highschool.schoolpi.net/api/vocational_lists/view', [
//                'query' => [
//                    'schoolid' => 23,
//                    'mark' => $value['id'],
//                ]
//            ]);
            $response = curl_get("https://highschool.schoolpi.net/api/vocational_lists/view?schoolid=23&id={$value['id']}");

            $body = json_decode($response, true);
            $temp = [
                'title' => $value['title'],
                'cate_id' => 10,
                'img_urls' => json_encode($value['thumb']),
                'user_id' => 1,
                'content' => "",
                'create_time' => time(),
                'update_time' => time(),
                'read_count' => $value['hits']??0,
            ];
            $temp['content'] = $body['code'] == 1 ? $body['data']['content'] : "";
            $temp['pub_date'] = strtotime($body['data']['create_time']);
            $temp['read_count'] = $body['data']['hits'];
            array_push($data, $temp);
//            (new News())->insertData($temp);
//            return Show::success(['data' => $body['data']]);
        }

//        if ($body['code'] == 1) {
//            $temp['content'] = $body['data']['content'];
//            halt($temp);
////            (new News())->insertData($temp);
////            return Show::success(['data' => $body['data']]);
//        }
        return Show::success($data);

    }
}
