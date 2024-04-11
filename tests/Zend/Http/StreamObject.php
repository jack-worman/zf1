<?php

namespace Zend\Http;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class StreamObject
{
    private $tempFile;

    public function __construct($tempFile)
    {
        $this->tempFile = $tempFile;
    }

    public function __toString()
    {
        return $this->tempFile;
    }
}
