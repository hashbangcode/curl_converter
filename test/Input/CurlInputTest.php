<?php

namespace Hashbangcode\CurlConverter\Input\Test;

use PHPUnit\Framework\TestCase;
use Hashbangcode\CurlConverter\Input\CurlInput;

class CurlInputTest extends TestCase
{
  public function testBrokenCurlCommand()
  {
    $this->expectException('InvalidArgumentException');
    $notCurlString = 'monkey https://www.example.com';
    $input = new CurlInput();
    $curlParameters = $input->extract($notCurlString);
  }

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

  public function testMultilineCurlCommand()
  {
    $curlString = <<<EOD
curl 'https://www.example.com/example-from' \
  -X DELETE
  --compressed \
  --insecure
EOD;
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('DELETE', $curlParameters->getHttpVerb());
    $this->assertEquals('https://www.example.com/example-from', $curlParameters->getUrl());
    $this->assertTrue($curlParameters->isInsecure());
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
    $this->assertStringContainsString('text=grfg&op=Rot13&form_build_id=form-F7yTHeY6UrAhh28TFR0ciNAXORC-uqg-ZVeflBPZ5O4&form_token=U4zfxzaKXfUFJzX63L1c4u3dmLu9jVnXMwYVruAxWY8&form_id=example-from', $curlParameters->getData()[0]);
    $this->assertTrue($curlParameters->isInsecure());
    $headers = $curlParameters->getHeaders();
    $this->assertEquals('Connection: keep-alive', $headers[0]);
    $this->assertEquals('Cache-Control: max-age=0', $headers[1]);
    $this->assertEquals('Upgrade-Insecure-Requests: 1', $headers[2]);
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
    $this->assertEquals('HEAD', $curlParameters->getHttpVerb());
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

  public function testCurlCopiedFromPostman() {
    $curlString = <<<EOD
curl --location --request POST 'http://fiddle.jshell.net/echo/html/' \
  --header 'Origin: http://fiddle.jshell.net' \
  --header 'Accept-Encoding: gzip, deflate' \
  --header 'Accept-Language: en-US,en;q=0.8' \
  --header 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1' \
  --header 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8' \
  --header 'Accept: */*' \
  --header 'Referer: http://fiddle.jshell.net/_display/' \
  --header 'X-Requested-With: XMLHttpRequest' \
  --header 'Connection: keep-alive' \
  --data-raw 'msg1=wow&msg2=such&msg3=data'
EOD;
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);

    $this->assertEquals('http://fiddle.jshell.net/echo/html/', $curlParameters->getUrl());
    $this->assertEquals('POST', $curlParameters->getHttpVerb());
    $this->assertEquals(9, count($curlParameters->getHeaders()));
    $this->assertEquals('Origin: http://fiddle.jshell.net', $curlParameters->getHeaders()[0]);
    $this->assertEquals('msg1=wow&msg2=such&msg3=data', $curlParameters->getData()[0]);
  }

  public function testExtensiveCurlCommand() {
    $curlString = <<<EOD
curl -X POST -H "Content-Type: application/json" -d '{"key1":"value1", "key2":"value2"}' --insecure --user username:password --proxy http://proxy.example.com:8080 https://example.com/api/resource
EOD;
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);

    $this->assertEquals('username', $curlParameters->getUsername());
    $this->assertEquals('password', $curlParameters->getPassword());
    $this->assertEquals('POST', $curlParameters->getHttpVerb());
    $this->assertEquals('Content-Type: application/json', $curlParameters->getHeaders()[0]);
    $this->assertEquals('{"key1":"value1", "key2":"value2"}', $curlParameters->getData()[0]);
    $this->assertEquals('http://proxy.example.com:8080', $curlParameters->getProxy());
    $this->assertEquals('https://example.com/api/resource', $curlParameters->getUrl());
  }

  public function testSettingDataDoesNotChangeVerbToPost() {
    $curlString = <<<EOD
curl --request PUT 'https://www.example.com/test' --data 'somedata=123'
EOD;
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);

    $this->assertEquals('https://www.example.com/test', $curlParameters->getUrl());
    $this->assertEquals('PUT', $curlParameters->getHttpVerb());
    $this->assertEquals('somedata=123', $curlParameters->getData()[0]);
  }

  public function testCurlCommandWithDataFile() {
    $curlString = 'curl -u "demo" -X POST -d @file1.txt -d @file2.txt https://example.com/upload';
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);

    $this->assertEquals('https://example.com/upload', $curlParameters->getUrl());
    $this->assertEquals('POST', $curlParameters->getHttpVerb());
    $this->assertEquals('@file1.txt', $curlParameters->getData()[0]);
  }

  public function testMultiPartDataIsExtractedCorrectly() {
    $curlString = <<<EOD
curl -X POST https://www.example.com/test \
     -u API_KEY:123 \
     -d 'shipment[to_address][id]=adr_HrBKVA85' \
     -d 'shipment[from_address][id]=adr_VtuTOj7o' \
     -d 'shipment[parcel][id]=prcl_WDv2VzHp' \
     -d 'shipment[is_return]=true' \
     -d 'shipment[customs_info][id]=cstinfo_bl5sE20Y'
EOD;
    $input = new CurlInput();
    $curlParameters = $input->extract($curlString);

    $this->assertEquals('API_KEY', $curlParameters->getUsername());
    $this->assertEquals('123', $curlParameters->getPassword());

    $this->assertEquals('https://www.example.com/test', $curlParameters->getUrl());
    $this->assertEquals('POST', $curlParameters->getHttpVerb());
    $this->assertEquals('shipment[to_address][id]=adr_HrBKVA85', $curlParameters->getData()[0]);
    $this->assertEquals('shipment[from_address][id]=adr_VtuTOj7o', $curlParameters->getData()[1]);
    $this->assertEquals('shipment[parcel][id]=prcl_WDv2VzHp', $curlParameters->getData()[2]);
    $this->assertEquals('shipment[is_return]=true', $curlParameters->getData()[3]);
    $this->assertEquals('shipment[customs_info][id]=cstinfo_bl5sE20Y', $curlParameters->getData()[4]);
  }
}
