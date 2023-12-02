<?php

namespace Hashbangcode\CurlConverter\Test;

use Hashbangcode\CurlConverter\CurlParameters;
use PHPUnit\Framework\TestCase;

class CurlParameterTest extends TestCase
{
  public function testDataIsSet()
  {
    $curlParameters = new CurlParameters();
    $this->assertFalse($curlParameters->hasData());
    $curlParameters->setData(['key' => 'value']);
    $this->assertTrue($curlParameters->hasData());
  }

  public function testCredentialsAreSet()
  {
    $curlParameters = new CurlParameters();
    $this->assertFalse($curlParameters->areCredentialsSet());
    $curlParameters->setUsername('username');
    $curlParameters->setPassword('password');
    $this->assertTrue($curlParameters->areCredentialsSet());
  }
}