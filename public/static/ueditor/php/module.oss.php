<?php

/**
 * oss接口
**/
require_once ("OSS/autoload.php");
use OSS\OssClient;
use OSS\Core\OssException;

class Module_Oss{
    protected static $_instance;
    public $accessKeyId = "LTAIRsZlOIfCQjXL";
    public $accessKeySecret = "eQPfBPF4tv0doWtsG282CBlVZ9lety";
    public $endpoint = "oss-cn-hangzhou.aliyuncs.com";
    public $bucket= "goldcoin";


    public function __construct($object, $filePath)
    {
        define('ENVIRONMENT','test');
        try {
            $object = ENVIRONMENT.'/'.$object;//2019-3-13 目录调整
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);

            $ossClient->uploadFile($this->bucket, $object, $filePath);
            return true;
        } catch (OssException $e) {
            print $e->getMessage();
        }
    } 

}
