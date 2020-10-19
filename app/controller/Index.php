<?php
namespace app\controller;

use app\BaseController;
use app\common\lib\Show;

class Index extends BaseController
{
    public function index()
    {
        return Show::success();
    }

    public function test()
    {
        $xmlStr = file_get_contents('http://www.hfgyxx.com/rss/news_10601_1060108.xml');
        $obj = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $eJSON = json_encode($obj);
        $dJSON = json_decode($eJSON, true);
        foreach ($dJSON['channel']['item'] as $value) {
            $data = [
                'title' => $value['title'],
                'desc' => $value['description'],
                'cate_id' => 2,
                'xwbh' => $value['xwbh'],
                'img_urls' => json_encode((array)$value['enclosure']['@attributes']['url']),
                'pub_date' => $value['pubDate'],
                'user_id' => 1,
            ];
            halt($data);
        }
        return Show::success([]);
    }
}
