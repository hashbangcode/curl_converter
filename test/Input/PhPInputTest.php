<?php

namespace Hashbangcode\CurlConverter\Input\Test;

use Hashbangcode\CurlConverter\Input\PhpInput;
use PHPUnit\Framework\TestCase;

class PhPInputTest extends TestCase
{
  public function testBrokenPhpCommand()
  {
    $this->expectException('InvalidArgumentException');
    $notCurlString = 'dfhgldkfhgslkfdjg';
    $input = new PhpInput();
    $curlParameters = $input->extract($notCurlString);
  }

  public function testFormSubmitInput(){
    $curlString = '
<?php
$curl_handle = curl_init();
curl_setopt($curl_handle, CURLOPT_URL, "https://www.example.com/example-from");
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "");
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($curl_handle);
if (curl_errno($curl_handle)) {
  echo \'Error:\' . curl_error($curl_handle);
}
curl_close($curl_handle);
echo $result;%  
';
    $input = new PhpInput();
    $curlParameters = $input->extract($curlString);
    $this->assertEquals('https://www.example.com/example-from', $curlParameters->getUrl());
    $this->assertEquals(true, $curlParameters->isInsecure());
    $this->assertEquals('DELETE', $curlParameters->getHttpVerb());
  }

}