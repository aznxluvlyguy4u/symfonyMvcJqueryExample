<?php

namespace AppBundle\Service;


use AppBundle\Constant\Environment;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;

class AWSSimpleStorageService
{
    /** @var string */
    protected $region;

    /** @var string */
    private $version;

    /** @var string */
    private $accessKeyId;

    /** @var string */
    private $secretKey;

    /** @var S3Client; */
    private $s3Service;

    /** @var string */
    private $bucket;

    /** @var string */
    private $prefix;

    /** @var string */
    private $pathApppendage;

    /**
     * @var Credentials
     */
    private $awsCredentials;


    public function __construct($credentials = array(), $region, $version, $bucket, $prefix, $currentEnvironment = null)
    {
        $this->accessKeyId = $credentials[0];
        $this->secretKey = $credentials[1];

        $this->awsCredentials =  new Credentials($this->accessKeyId, $this->secretKey);
        $this->region = $region;
        $this->version = $version;
        $this->bucket = $bucket;
        $this->prefix = $prefix;

        $s3Config = array(
            'region'  => $this->region,
            'version' => $this->version,
            'credentials' => $this->awsCredentials
        );

        $this->s3Service = new S3Client($s3Config);


        /**
         * Get current environment, set separate files based on environment
         */
        switch($currentEnvironment) {
            case Environment::PROD:
                $this->pathApppendage = $this->prefix."/";
                break;
            case Environment::STAGE:
                $this->pathApppendage = $this->prefix.'/stage/';
                break;
            case Environment::DEV:
                $this->pathApppendage = $this->prefix.'/dev/';
                break;
            case Environment::TEST:
                $this->pathApppendage = $this->prefix.'/dev/';
                break;
            case Environment::LOCAL:
                $this->pathApppendage = $this->prefix.'/dev/';
                break;
            default;
                $this->pathApppendage = $this->prefix.'/dev/';
                break;
        }
    }

    /**
     * @return S3Client
     */
    public function getS3Client()
    {
        return $this->s3Service;
    }


    /**
     * Upload a file without a filepath to the location in the S3 Bucket specified by the key.
     * And return the download url.
     *
     * @param $file
     * @param $key
     * @return string
     */
    public function uploadPdf($file, $key)
    {
        return $this->upload($file, $key, 'application/pdf');
    }

    /**
     * Upload a file with the given filepath to the location in the S3 Bucket specified by the key.
     * And return the download url.
     *
     * @param $filepath
     * @param $key
     * @return string
     */
    public function uploadPdfFromFilePath($filepath, $key)
    {
        return $this->uploadFromFilePath($filepath, $key, 'application/pdf');
    }

    /**
     * Upload a file directly to the location in the S3 Bucket specified by the key.
     * And return the download url.
     *
     * @param $file
     * @param $key
     * @return string
     */
    public function upload($file, $key, $contentType)
    {
        $key = $this->pathApppendage.$key;

        $result = $this->s3Service->putObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $key,
            'Body'   => $file,
            'ACL'    => 'private', //protect access to the uploaded file
            'ContentType' => $contentType
        ));

        $command = $this->s3Service->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);

        $request = $this->s3Service->createPresignedRequest($command, '+20 minutes');
        $url = (string) $request->getUri(); //The S3 download link including the accesstoken

        return $url;
    }

    /**
     * Upload a file with the given filepath to the location in the S3 Bucket specified by the key.
     * And return the download url.
     *
     * @param $filepath
     * @param $key
     * @return string
     */
    public function uploadFromFilePath($filepath, $key, $contentType)
    {
        return $this->upload(file_get_contents($filepath), $key, $contentType);
    }

    /**
     * Get Object
     */
    public function getPresignedUrl($key)
    {
        $key = $this->pathApppendage.$key;
        $command = $this->s3Service->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);
        
        $request = $this->s3Service->createPresignedRequest($command, '+30 minutes');
        $url = (string) $request->getUri(); //The S3 download link including the accesstoken

        return $url;
    }
}