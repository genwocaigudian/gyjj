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
//        $xmlStr = file_get_contents('http://www.hfgyxx.com/rss/news_10601_1060108.xml');
//        $xml = new \SimpleXMLElement($xmlStr);
//        $res = $xml->xpath();

        $values = simplexml_load_file('http://www.hfgyxx.com/rss/news_10601_1060108.xml');

        foreach ($values as $value) {
            var_dump($value->item);die();
        }
        return Show::success([]);
    }
}
