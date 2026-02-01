<?php

namespace Hashbangcode\CurlConverter\Test;

use Hashbangcode\CurlConverter\Input\CurlInput;
use Hashbangcode\CurlConverter\Output\CurlOutput;
use Hashbangcode\CurlConverter\Output\PhpOutput;
use PHPUnit\Framework\TestCase;
use Hashbangcode\CurlConverter\CurlConverter;

class CurlConverterTest extends TestCase
{
  public function testCurlCommandToPhp()
  {
    $command = 'curl https://www.example.com/';

    $input = new CurlInput();
    $output = new PhpOutput();

    $converter = new CurlConverter($input, $output);
    $converted = $converter->convert($command);

    $this->assertIsString($converted);
    $this->assertStringContainsString('<?php', $converted);
    $this->assertStringContainsString('https://www.example.com', $converted);
  }

  public function testCurlCommandToCurlCommand()
  {
    $command = 'curl https://www.example.com/';

    $input = new CurlInput();
    $output = new CurlOutput();

    $converter = new CurlConverter($input, $output);
    $converted = $converter->convert($command);

    $this->assertEquals($command, $converted);
  }

  public function testCurlPostDataToPhP()
  {
    $command = <<<EOD
curl -sSL -X POST https://www.example.com/api/resource -d '{"operationName":"getPastGroupEvents","variables":{"urlname":"test","beforeDateTime":"2026-01-30T08:12:37.288Z","after":"MzA3NjgxNTk3OjE3NDcxNTkyMDAwMDA="},"extensions":{"persistedQuery":{"version":1,"sha256Hash":""}}}'
EOD;
    $input = new CurlInput();
    $output = new PhpOutput();

    $converter = new CurlConverter($input, $output);
    $converted = $converter->convert($command);

    $this->assertIsString($converted);
    $this->assertStringContainsString('<?php', $converted);
    $this->assertStringContainsString('https://www.example.com/api/resource', $converted);
    $this->assertStringContainsString('{"operationName":"getPastGroupEvents","variables":{"urlname":"test","beforeDateTime":"2026-01-30T08:12:37.288Z","after":"MzA3NjgxNTk3OjE3NDcxNTkyMDAwMDA="},"extensions":{"persistedQuery":{"version":1,"sha256Hash":""}}}', $converted);
  }
}