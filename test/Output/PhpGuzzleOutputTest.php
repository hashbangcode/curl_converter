<?php

namespace Hashbangcode\CurlConverter\Output\Test;

use Hashbangcode\CurlConverter\CurlParameters;
use PHPUnit\Framework\TestCase;
use Hashbangcode\CurlConverter\Output\PhpOutput;

class PhpGuzzleOutputTest extends TestCase
{
  public function testPhpOutput()
  {
    $curlParameters = new CurlParameters();
    $curlParameters->setUrl('https://www.hashbangcode.com/');

    $phpOutput = new PhpOutput();
    $output = $phpOutput->render($curlParameters);

    $this->assertStringContainsString('<?php', $output);
    $this->assertStringContainsString('https://www.hashbangcode.com/', $output);
    $this->assertStringContainsString('GET', $output);
  }

}
