<?php
/**
 * Created by PhpStorm.
 * User: philipnorton42
 * Date: 13/06/2021
 * Time: 12:32
 */

namespace Hashbangcode\CurlConverter\Output\Test;

use Hashbangcode\CurlConverter\CurlParameters;
use PHPUnit\Framework\TestCase;
use Hashbangcode\CurlConverter\Output\PhpOutput;

class PhpOutputTest extends TestCase
{
  public function testPhpOutput()
  {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');

    $phpOutput = new PhpOutput();
    $output = $phpOutput->render($curlParameters);

    $this->assertStringContainsString('<?php', $output);
  }

  public function testPhpHeadersOutput()
  {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');
    $headers = [
      'Cache-Control: max-age=0',
      'Accept-Language: en-GB',
      'Accept: text/html',
    ];
    $curlParameters->setHeaders($headers);
    $curlParameters->setIsInsecure(true);

    $phpOutput = new PhpOutput();
    $output = $phpOutput->render($curlParameters);

    $this->assertStringContainsString('<?php', $output);
    $this->assertStringContainsString('Cache-Control: max-age=0', $output);
    $this->assertStringContainsString('Accept-Language: en-GB', $output);
    $this->assertStringContainsString('Accept: text/html', $output);
    $this->assertStringContainsString('CURLOPT_SSL_VERIFYPEER, false', $output);
  }

  public function test()
  {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');
    $curlParameters->setFollowRedirects(true);

    $phpOutput = new PhpOutput();
    $output = $phpOutput->render($curlParameters);

    $this->assertStringContainsString('CURLOPT_FOLLOWLOCATION, true', $output);
  }
}
