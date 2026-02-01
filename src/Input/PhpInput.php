<?php

namespace Hashbangcode\CurlConverter\Input;

use Hashbangcode\CurlConverter\CurlParametersInterface;
use Hashbangcode\CurlConverter\CurlParameters;

/**
 * Class PhpInput.
 *
 * @package Hashbangcode\CurlConverter\Input
 */
class PhpInput implements InputInterface
{
  /**
   * {@inheritDoc}
   */
  public function extract(string $data): CurlParametersInterface
  {
    $data = trim($data);
    if (strpos($data, '<?php') !== 0) {
      throw new \InvalidArgumentException('Not a PHP script.');
    }

    $curlParameters = new CurlParameters();

    if (preg_match('/CURLOPT_URL,\s?[\'|"](.*?)[\'|"]/', $data, $curloptUrl) == 1) {
      $curlParameters->setUrl($curloptUrl[1]);
    }

    if (preg_match('/CURLOPT_POST,\s?[\'|"]?(.*?)([\'|"])?\)/', $data, $curloptUrl) == 1) {
      $curlParameters->setHttpVerb('POST');
    }

    if (preg_match('/CURLOPT_CUSTOMREQUEST,\s?[\'|"](.*?)[\'|"]/', $data, $curloptUrl) == 1) {
      $curlParameters->setHttpVerb(strtoupper($curloptUrl[1]));
    }

    if (preg_match('/CURLOPT_SSL_VERIFYPEER,\s?[\'|"]?(.*?)([\'|"])?\)/', $data, $curloptUrl) == 1) {
      if ($curloptUrl[1] == 'false') {
        $curlParameters->setIsInsecure(true);
      }
    }

    if (preg_match('/CURLOPT_POSTFIELDS,\s?[\'|"]?(.*?)([\'|"])?\)/', $data, $curloptUrl) == 1) {
        $curlParameters->addData($curloptUrl[1]);
        if ($curlParameters->getHttpVerb() === 'GET') {
          $curlParameters->setHttpVerb('POST');
        }
    }

    return $curlParameters;
  }

}