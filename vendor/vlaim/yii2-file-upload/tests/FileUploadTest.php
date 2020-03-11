<?php

namespace vlaim\fileupload;

use PHPUnit\Framework\TestCase;


class FileUploadTest extends TestCase{
    
    /**
     * @covers            vlaim\fileupload\FileUpload::__construct
     * @uses              vlaim\fileupload\FileUpload
     * @expectedException vlaim\fileupload\FileUploadException
     */
    public function testExceptionIsRaisedForInvalidConstructorArguments()
    {
        new FileUpload(null, null);
    }
    
    /**
     * @covers            vlaim\fileupload\FileUpload::__construct
     * @uses              vlaim\fileupload\FileUpload
     * @expectedException vlaim\fileupload\FileUploadException
     */
    public function testExceptionIsRaisedForInvalidConstructorArguments2()
    {
        new FileUpload(100, []);
    }
    
    
    
}