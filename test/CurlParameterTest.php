<?php
/**
 * Created by PhpStorm.
 * User: philipnorton42
 * Date: 13/06/2021
 * Time: 12:32
 */

namespace Hashbangcode\CurlConverter\Test;

use Hashbangcode\CurlConverter\CurlParameters;
use Hashbangcode\CurlConverter\Input\CurlInput;
use Hashbangcode\CurlConverter\Output\PhpOutput;
use PHPUnit\Framework\TestCase;
use Hashbangcode\CurlConverter\CurlConverter;

class CurlParameterTest extends TestCase
{
  public function testCredentialsAreSet() {

    $curlParameters = new CurlParameters();
    $this->assertFalse($curlParameters->areCredentialsSet());
    $curlParameters->setUsername('username');
    $curlParameters->setPassword('password');
    $this->assertTrue($curlParameters->areCredentialsSet());
  }
}