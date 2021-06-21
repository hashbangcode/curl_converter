<?php
/**
 * Created by PhpStorm.
 * User: philipnorton42
 * Date: 13/06/2021
 * Time: 12:32
 */

namespace Hashbangcode\CurlConverter\Input\Test;

use PHPUnit\Framework\TestCase;
use Hashbangcode\CurlConverter\Input\CurlInput;

class CurlInputTest extends TestCase
{
  public function testCurlInputWithSimpleCommand()
  {
    $curlString = "curl https://www.example.com/";
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('https://www.example.com/', $curlParameters->getUrl());
  }

  public function testCurlInputWithEmptyPostCommand()
  {
    $curlString = "curl -X POST https://www.example.com/";
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('POST', $curlParameters->getHttpVerb());
    $this->assertEquals('https://www.example.com/', $curlParameters->getUrl());
  }

  public function testCurlInputWithCredentials()
  {
    $curlString = "curl -u username:password https://www.example.com/";
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertTrue($curlParameters->areCredentialsSet());
    $this->assertEquals('username', $curlParameters->getUsername());
    $this->assertEquals('password', $curlParameters->getPassword());
    $this->assertEquals('https://www.example.com/', $curlParameters->getUrl());
  }

  public function testCurlInputWithDataPostCommand()
  {
    $curlString = <<<EOD
curl 'https://www.example.com/example-from' \
  -H 'Connection: keep-alive' \
  -H 'Cache-Control: max-age=0' \
  -H 'Upgrade-Insecure-Requests: 1' \
  -H 'Origin: http://www.example.com' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.106 Safari/537.36' \
  -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9' \
  -H 'Referer: http://www.example.com/example-from' \
  -H 'Accept-Language: en-GB,en;q=0.9,cy-GB;q=0.8,cy;q=0.7,en-US;q=0.6' \
  --data-raw 'text=grfg&op=Rot13&form_build_id=form-F7yTHeY6UrAhh28TFR0ciNAXORC-uqg-ZVeflBPZ5O4&form_token=U4zfxzaKXfUFJzX63L1c4u3dmLu9jVnXMwYVruAxWY8&form_id=example-from' \
  --compressed \
  --insecure
EOD;
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('POST', $curlParameters->getHttpVerb());
    $this->assertEquals('https://www.example.com/example-from', $curlParameters->getUrl());
    $this->assertStringContainsString('text=grfg&op=Rot13&form_build_id=form-F7yTHeY6UrAhh28TFR0ciNAXORC-uqg-ZVeflBPZ5O4&form_token=U4zfxzaKXfUFJzX63L1c4u3dmLu9jVnXMwYVruAxWY8&form_id=example-from', $curlParameters->getData());
    $this->assertTrue($curlParameters->isInsecure());
  }

  public function testCurlInputCoppiedFromChrome()
  {
    $curlString = <<<EOD
curl 'https://www.example.com/' \
  -H 'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'Upgrade-Insecure-Requests: 1' \
  -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36' \
  -H 'Referer: https://www.example.com/' \
  --compressed
EOD;
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('https://www.example.com/', $curlParameters->getUrl());
    $this->assertEquals(5, count($curlParameters->getHeaders()));
  }

  public function testCurlInputWithHttpVerb()
  {
    $curlString = 'curl -X DELETE "http://www.example.com/service?id=123"';
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('DELETE', $curlParameters->getHttpVerb());
    $this->assertEquals('http://www.example.com/service?id=123', $curlParameters->getUrl());
  }

  public function testCurlInputWithMultiFlags()
  {
    $curlString = 'curl -sSLIXGET https://www.example.com';
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('GET', $curlParameters->getHttpVerb());
    $this->assertEquals('https://www.example.com', $curlParameters->getUrl());
  }

  public function testCurlInputWithUrlParameter()
  {
    $curlString = 'curl --url https://www.example.com';
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('GET', $curlParameters->getHttpVerb());
    $this->assertEquals('https://www.example.com', $curlParameters->getUrl());
  }

  public function testCurlFollowRedirects()
  {
    $curlString = 'curl -L https://www.example.com';
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('GET', $curlParameters->getHttpVerb());
    $this->assertEquals('https://www.example.com', $curlParameters->getUrl());
    $this->assertTrue($curlParameters->followRedirects());
  }
}
