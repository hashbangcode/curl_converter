<?php

namespace Hashbangcode\CurlConverter\Output;

use Hashbangcode\CurlConverter\CurlParametersInterface;

/**
 * Class PhpOutput.
 *
 * @package Hashbangcode\CurlConverter\Output
 */
class PhpOutput implements OutputInterface
{

  /**
   * {@inheritdoc}
   */
  public function render(CurlParametersInterface $curlParameters): string
  {
    $output = '';

    $output .= '<?php' . PHP_EOL;

    $output .= '$curl_handle = curl_init();' . PHP_EOL;

    $output .= 'curl_setopt($curl_handle, CURLOPT_URL, "' . $curlParameters->getUrl() . '");' . PHP_EOL;
    $output .= 'curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);' . PHP_EOL;

    if ($curlParameters->followRedirects()) {
      $output .= 'curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);';
    }

    switch ($curlParameters->getHttpVerb()) {
      case 'POST':
        $output .= 'curl_setopt($curl_handle, CURLOPT_POST, 1);' . PHP_EOL;
        break;
      default:
        $output .= 'curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "' . $curlParameters->getHttpVerb() . '");' . PHP_EOL;
    }

    $output .= 'curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "' . http_build_query($curlParameters->getData()) . '");' . PHP_EOL;

    $headers = $curlParameters->getHeaders();
    if (count($headers) > 0) {
      $output .= '$headers = [];' . PHP_EOL;
      foreach ($headers as $header) {
        $output .= '$headers[] = "' . $header . '";' . PHP_EOL;
      }
      $output .= 'curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);' . PHP_EOL;
    }

    if ($curlParameters->isInsecure()) {
      $output .= 'curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);' . PHP_EOL;
    }

    if ($curlParameters->areCredentialsSet()) {
      $output .= 'curl_setopt($ch, CURLOPT_USERPWD, "' . $curlParameters->getUsername() . ':' . $curlParameters->getPassword() . '");' . PHP_EOL;
    }

    $output .= '$result = curl_exec($curl_handle);' . PHP_EOL;

    $output .= 'if (curl_errno($curl_handle)) {' . PHP_EOL;
    $output .= '  echo \'Error:\' . curl_error($curl_handle);' . PHP_EOL;
    $output .= '}' . PHP_EOL;

    $output .= 'curl_close($curl_handle);' . PHP_EOL;

    $output .= 'echo $result;';

    return $output;
  }

}