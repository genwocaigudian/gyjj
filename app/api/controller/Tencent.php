<?php
namespace app\api\controller;

use app\common\lib\Show;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
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
			$params = ["Limit" => 100, "Offest" => 0];
			$req->fromJsonString(json_encode($params));
			$resp = $client->SearchMedia($req);
		} catch (TencentCloudSDKException $e) {
			return Show::error($e->getMessage());
		}
		
		return Show::success(json_decode($resp->toJsonString(), true));
	}
}
