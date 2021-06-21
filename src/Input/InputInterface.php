<?php

namespace Hashbangcode\CurlConverter\Input;

use Hashbangcode\CurlConverter\CurlParametersInterface;

/**
 * Interface InputInterface.
 *
 * @package Hashbangcode\CurlConverter\Input
 */
interface InputInterface
{
  /**
   * Extract the data of the command into a instance of CurlParametersInterface.
   *
   * @param string $data
   *   The data to extract.
   *
   * @return CurlParametersInterface
   *   The parsed commands in an instance of CurlParametersInterface.
   */
  public function extract(string $data): CurlParametersInterface;
}