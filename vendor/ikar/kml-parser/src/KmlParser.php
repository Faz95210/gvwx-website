<?php namespace ikar\kmlparser;

use Yii;
use common\models\Trip;

class KmlParserException extends \Exception
{

}

class KmlParser
{
    const S_LOCAL = 1;
    const S_S3    = 2;

    private $_storage;
    private $_path;
    private $_storageAuthParams;
    private $_xml;

    /**
     *
     * @param integer $storage               1 - Local, 2 — Amazon S3
     *
     * @throws        \vlaim\FileUpload\FileUploadException   Throws exception if storage type is undefined
     */
    public function __construct($storage, $storageAuthParams = [])
    { 
        if($storage != self::S_LOCAL && $storage != self::S_S3){
            throw new KmlParserException("Undefined storage. Use 1 (KmlParser::S_LOCAL) for Local and 2 (KmlParser::S_S3) for Amazon S3");
        }
        
        $this->_storage           = $storage;
        $this->_storageAuthParams = $storageAuthParams;
    }


    public function load($path)
    {
        $this->_path = $path;

        switch ($this->_storage) {
            case self::S_LOCAL: {
                $this->_xml = $this->loadFromFs();
                break;
            }
            case self::S_S3: {
                $this->_xml = $this->loadFromS3();
                break;
            }
        }
    }

    public function debug()
    {
        \Yii::info("Xml File: ", 'logs');
        \Yii::info($this->_xml->{'Document'}->{'Placemark'}->{'LineString'}->{'coordinates'}, 'logs');
    }

    private function loadFromFs()
    {
        if (file_exists($this->_path)) {
            return simplexml_load_file($this->_path);
        } else {
            throw new KmlParserException("Xml file not found on local FS");
        }
    }

    private function loadFromS3() {
        $this->checkRequiredAWSParams();

        $s3 = S3Client::factory($this->_storageAuthParams);

        if (!$s3->doesBucketExist($this->_storageAuthParams['bucket'])) {
            throw new KmlParserException("S3 Bucket does not exists");
        }

        $download= $s3->getObject(
            array(
             'Bucket'       => $this->_storageAuthParams['bucket'],
             'Key'          => $this->_path,
            )
        );

        return simplexml_load_string($download["body"]);
    }

    public function getStartDateTime()
    {
        return $this->getXmlParam('startDateTime');
    }

    public function getStopDateTime()
    {
        return $this->getXmlParam('stopDateTime');
    }

    public function getStartCoordinates()
    {
        return array("lat" => $this->getXmlParam('startLatitude'), "lng" => $this->getXmlParam('startLongitude'));
    }

    public function getStopCoordinates()
    {
        return array("lat" => $this->getXmlParam('stopLatitude'), "lng" => $this->getXmlParam('stopLongitude'));
    }

    public function getDistance()
    {
        return $this->getXmlParam('distance');
    }

    public function getDuration()
    {
        return $this->getXmlParam('duration');
    }


    private function getXmlParam($param)
    {
        if ($this->_xml !== null) {
            return $this->_xml->{'Document'}->{$param};
        } else {
            return null;
        }
    }

    public function getPath()
    {
        return $this->_path;
    }


    /**
     *
     * @throw FileUploadException   Throws exception if some params are missed
     */
    final function checkRequiredAWSParams()
    {
        if (empty($this->_storageAuthParams['credentials']['key'])) {
            throw new KmlParserException('AWSParams.credentials.key is required');
        }

        if (empty($this->_storageAuthParams['credentials']['secret'])) {
            throw new KmlParserException('AWSParams.credentials.secret is required');
        }

        if (empty($this->_storageAuthParams['bucket'])) {
            throw new KmlParserException('AWSParams.bucket is required');
        }

        if (empty($this->_storageAuthParams['region'])) {
            throw new KmlParserException('AWSParams.region is required');
        }
    }
}
