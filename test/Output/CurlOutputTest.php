<?php

namespace Hashbangcode\CurlConverter\Output\Test;

use Hashbangcode\CurlConverter\CurlParameters;
use Hashbangcode\CurlConverter\Output\CurlOutput;
use PHPUnit\Framework\TestCase;
use Hashbangcode\CurlConverter\Output\PhpOutput;

class CurlOutputTest extends TestCase
{
  public function testCurlOutput()
  {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');

    $phpOutput = new CurlOutput();
    $output = $phpOutput->render($curlParameters);

    $this->assertEquals('curl https://www.hashbangcode.com/', $output);
  }

  public function testCurlFollowRedirectsOutput()
  {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');
    $curlParameters->setFollowRedirects(true);

    $curlOutput = new CurlOutput();
    $output = $curlOutput->render($curlParameters);

    $this->assertEquals('curl --location https://www.hashbangcode.com/', $output);
  }

  public function testCurlHttpVerbOutput() {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');
    $curlParameters->setFollowRedirects(true);
    $curlParameters->setHttpVerb('PUT');

    $curlOutput = new CurlOutput();
    $output = $curlOutput->render($curlParameters);

    $this->assertEquals('curl --location --request PUT https://www.hashbangcode.com/', $output);
  }

  public function testCurlHeadersOutput() {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');
    $headers = [
      'Cache-Control: max-age=0',
      'Accept-Language: en-GB',
      'Accept: text/html',
    ];
    $curlParameters->setHeaders($headers);

    $curlOutput = new CurlOutput();
    $output = $curlOutput->render($curlParameters);

    $this->assertEquals('curl -H \'Cache-Control: max-age=0\' -H \'Accept-Language: en-GB\' -H \'Accept: text/html\' https://www.hashbangcode.com/', $output);
  }

  public function testCurlInsecureOutput() {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');
    $curlParameters->setIsInsecure(true);

    $curlOutput = new CurlOutput();
    $output = $curlOutput->render($curlParameters);

    $this->assertEquals('curl --insecure https://www.hashbangcode.com/', $output);
  }

  public function testCurlUsernameAndPasswordSetOutput() {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');
    $curlParameters->setUsername('username');
    $curlParameters->setPassword('password');

    $curlOutput = new CurlOutput();
    $output = $curlOutput->render($curlParameters);

    $this->assertEquals('curl --user username:password https://www.hashbangcode.com/', $output);
  }

  public function testCurlDataIsOutput() {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');
    $curlParameters->setData('something=123');
    $curlParameters->setHttpVerb('POST');

    $curlOutput = new CurlOutput();
    $output = $curlOutput->render($curlParameters);

    $this->assertEquals('curl --request POST --data \'something=123\' https://www.hashbangcode.com/', $output);
  }
}
