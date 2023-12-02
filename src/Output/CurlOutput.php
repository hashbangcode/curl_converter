<?php

namespace Hashbangcode\CurlConverter\Output;

use Hashbangcode\CurlConverter\CurlParametersInterface;

/**
 * Class CurlOutput.
 *
 * @package Hashbangcode\CurlConverter\Output
 */
class CurlOutput implements OutputInterface
{

  /**
   * {@inheritdoc}
   */
  public function render(CurlParametersInterface $curlParameters): string
  {
    $output = 'curl ';

    if ($curlParameters->followRedirects()) {
      $output .= '--location ';
    }

    if ($curlParameters->getHttpVerb() !== 'GET') {
      $output .= '--request ' . $curlParameters->getHttpVerb() . ' ';
    }

    if ($curlParameters->isInsecure()) {
      $output .= '--insecure ';
    }

    if ($curlParameters->areCredentialsSet()) {
      $output .= '--user username:password ';
    }

    foreach ($curlParameters->getHeaders() as $header) {
      $output .= '-H \'' . $header . '\' ';
    }

    if ($curlParameters->hasData()) {
      foreach ($curlParameters->getData() as $id => $data) {
        if ($id) {
          $output .= '--data \'' . $id . '=' . $data . '\' ';
        }
        else {
          $output .= '--data \'' . $data . '\' ';
        }
      }
    }

    $output .= $curlParameters->getUrl();

    return $output;
  }

}