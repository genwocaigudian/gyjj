<?php
namespace app\api\controller;

use app\common\lib\Show;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\DescribeAllClassRequest;
use TencentCloud\Vod\V20180717\Models\SearchMediaRequest;
use TencentCloud\Vod\V20180717\VodClient;

class Tencent extends AuthBase
{
	protected $config;
	protected $secrctId = '';
	protected $secrctKey = '';
	protected $url = '';
	
	public function __construct()
	{
		$this->config = config('video');
		$this->secrctId = $this->config['secret_id'];
		$this->secrctKey = $this->config['secret_key'];
		$this->url = $this->config['url'];
	}
	
	public function index()
	{
		try {
			$cred = new Credential($this->secrctId, $this->secrctKey);
			$httpProfile = new HttpProfile();
			$httpProfile->setEndpoint($this->url);
			
			$clientProfile = new ClientProfile();
			$clientProfile->setHttpProfile($httpProfile);
			$client = new VodClient($cred, "", $clientProfile);
			
			$req = new SearchMediaRequest();
			$params = ["Limit" => 50, "Offest" => 0, "Categories" => ["Video"]];
//			$params = [];
			$req->fromJsonString(json_encode($params));
			$resp = $client->SearchMedia($req);

			$videoList = json_decode($resp->toJsonString(), true);
		} catch (TencentCloudSDKException $e) {
			return Show::error($e->getMessage());
		}

		$insertData = [];

        foreach ($videoList['MediaInfoSet'] as $item) {
            $baseInfo = $item['BasicInfo'];
            $createTime = strtotime($baseInfo['CreateTime']);
            $pathInfo = pathinfo($baseInfo['MediaUrl']);
            if (!in_array($pathInfo['extension'], ['mp4'])) {
                continue;
            }
            $insertData[] = [
                'title' => $baseInfo['Name'],
                'vid' => $baseInfo['Vid'],
                'class_id' => $baseInfo['ClassId'],
                'media_url' => $baseInfo['MediaUrl'],
                'cover_url' => $baseInfo['CoverUrl'],
                'upload_time' => $createTime,
            ];
        }

        $res = (new \app\common\services\Video())->insertAll($insertData);

		return Show::success($res);
	}


    public function clist()
    {
        try {
            $cred = new Credential($this->secrctId, $this->secrctKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint($this->url);

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, "", $clientProfile);

            $req = new DescribeAllClassRequest();

            $params = array(

            );
            $req->fromJsonString(json_encode($params));

            $resp = $client->DescribeAllClass($req);

            $cateList = json_decode($resp->toJsonString(), true);
        } catch (TencentCloudSDKException $e) {
            return Show::error($e->getMessage());
        }

        $insertData = [];

        foreach ($cateList['ClassInfoSet'] as $item) {
            if (!$item['ClassId']) {
                continue;
            }
            $insertData[] = [
                'class_id' => $item['ClassId'],
                'class_name' => $item['ClassName'],
                'level' => $item['Level'],
            ];
        }

        $res = (new \app\common\services\VideoCategory())->insertAll($insertData);

        return Show::success($res);
    }
}
