<?php

namespace Hashbangcode\CurlConverter\Output;

use Hashbangcode\CurlConverter\CurlParametersInterface;

/**
 * Interface OutputInterface.
 *
 * @package Hashbangcode\CurlConverter\Output
 */
interface OutputInterface
{
  /**
   * Convert an instance of CurlParametersInterface into a executable command.
   *
   * @param CurlParametersInterface $curlParameters
   *   An instance of CurlParametersInterface.
   *
   * @return string
   *   An executable curl command.
   */
  public function render(CurlParametersInterface $curlParameters): string;
}