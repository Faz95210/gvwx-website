# Yii2 File Upload

[![Stable Version](https://poser.pugx.org/vlaim/yii2-file-upload/v/stable)](https://packagist.org/packages/vlaim/yii2-file-upload) [![License](https://poser.pugx.org/vlaim/yii2-file-upload/license)](https://packagist.org/packages/vlaim/yii2-file-upload)

**Yii2 FileUpload** – PHP library for uploading files to your server or [Amazon S3](https://aws.amazon.com/ru/documentation/s3). It makes easy for developers to handle with yii2 UploadedFile instances. It's also possible to upload files via URLs from external sources.

## Getting Started

### Installation

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

> Note: Check the [composer.json](https://github.com/vlaim/yii2-file-upload/blob/master/composer.json) for this extension's requirements and dependencies. Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

Either run

`$ php composer.phar require vlaim/yii2-file-upload "dev-master"`

or add

`"vlaim/yii2-file-upload": "dev-master"`

to the `require` section of your `composer.json` file.

**Do not forget include Composer autoloader and define namespace for library**

```php 
<?php
require '/path/to/vendor/autoload.php'; 
use vlaim\fileupload\FileUpload;
```


## Quick Examples



### Upload a file to your local server

```php 
$photo = UploadedFile::getInstance($model, 'photo'); 
$uploader = new FileUpload(FileUpload::S_LOCAL);
```
### Upload a file to Amazon S3

This code uploads a file to Amazon S3. You must provide an associative array as second argument in FileUpload constructor in following way:

```php 
$photo = UploadedFile::getInstance($model, 'photo'); 
$uploader = new FileUpload(FileUpload::S_S3, [
    'version' => 'latest',
    'region' => '<regiongoeshere>',
    'credentials' => [
        'key' => '<keygoeshere>',
        'secret' => '<secretgoeshere>'
    ],
    'bucket' => '<bucketgoeshere>'
]);
```

## Methods

### setUploadFolder(string $folder)

Sets folder name in which files will be uploaded to.

**Default to 'uploads'**

```php 
$uploader->setUploadFolder('photos');
```

### setFsPath(string $fsPath)
**(Only for Local mode)**

Sets path in which files will be uploaded to. You can provide absolute or relative path 


**Default to /**

```php 
$uploader->setFsPath('/var/www/path/to/your/app/');
```

### setFsUrl(string $url)

**(Only for Local mode)** 

Sets url. For example, if you set path to 'http://static.example.com' file after uploading will have URL http://static.example.com/path/to/your/file

**Default to /** 

```php
$uploader->setFsPath('http://pathtoyoursite.com');
```

### hashFilename(boolean $hash)

Defines if upload filename needs to be hashed using md5 algorythm in following way: 

```php 
md5($fileName . time() . mt_rand(0, 30) // file.png upload filename will be 2122c3a6ad9997af28cab44b7fe7ab90.jpg
```

 **Default to true**


```php 
$uploader->hashFilename(false);
```

### setACL(string $acl)

Sets Access Control List.

Read more at [http://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html](http://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html)

```php 
$uploader->setACL('public-read');
```

## Catching and handling exceptions
To catch exceptions use FileUploadException class

```php 
<?php
use vlaim\fileupload\FileUploadException;

try{
	//your code goes here
}
catch(FileUploadException $e){
	echo $e->getMessage();
}

```

## Tests
Will be added soon :)

Issues
------

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/squizlabs/PHP_CodeSniffer/issues).


## License

**yii2-file-upload** is released under the MIT License. See the bundled LICENSE.md for details.