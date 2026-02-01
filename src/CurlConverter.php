<?php

namespace Hashbangcode\CurlConverter;

use Hashbangcode\CurlConverter\Input\InputInterface;
use Hashbangcode\CurlConverter\Output\OutputInterface;

/**
 * Class CurlConverter.
 *
 * @package Hashbangcode\CurlConverter
 */
class CurlConverter
{
  /**
   * The input object.
   * @var InputInterface
   */
  protected $input;

  /**
   * The output object.
   * @var OutputInterface
   */
  protected $output;

  /**
   * The CurlParameters object.
   *
   * @var CurlParameters
   */
  protected $curlParameters;

  /**
   * CurlConverter constructor.
   *
   * @param InputInterface $input
   *   The input interface.
   * @param OutputInterface $output
   *   The output interface.
   */
  public function __construct(InputInterface $input = nullable, OutputInterface $output = nullable)
  {
    if ($input !== null) {
      $this->input = $input;
    }
    if ($output !== null) {
      $this->output = $output;
    }
  }

  /**
   * Convert!
   *
   * @param string $input
   *   The input string to convert using the input and output objects.
   *
   * @return string
   *   The resulting output string.
   *
   * @throws \Exception
   */
  public function convert(string $input)
  {
    if ($this->input === null) {
      throw new \Exception('Input object is not set!');
    }
    if ($this->output === null) {
      throw new \Exception('Output object is not set!');
    }
    $this->setCurlParameters($this->input->extract($input));
    return $this->output->render($this->getCurlParameters());
  }

  /**
   * Get the input object.
   *
   * @return InputInterface
   */
  public function getInput(): InputInterface
  {
    return $this->input;
  }

  /**
   * Set the input object.
   *
   * @param InputInterface $input
   *   The input object.
   *
   * @return CurlConverter
   */
  public function setInput(InputInterface $input): CurlConverter
  {
    $this->input = $input;
    return $this;
  }

  /**
   * @return OutputInterface
   */
  public function getOutput(): OutputInterface
  {
    return $this->output;
  }

  /**
   * Set the output object.
   *
   * @param OutputInterface $output
   *   The output object.
   *
   * @return CurlConverter
   */
  public function setOutput(OutputInterface $output): CurlConverter
  {
    $this->output = $output;
  }

  /**
   * Get the instance of the CurlParametersInterface.
   *
   * This will be automatically generated during the convert() method call and can be used to tweak the command
   * parameters if needed.
   *
   * @return CurlParametersInterface
   */
  public function getCurlParameters(): CurlParametersInterface
  {
    return $this->curlParameters;
  }

  /**
   * Set the instance of CurlParametersInterface.
   *
   * @param CurlParametersInterface $curlParameters
   *   The CurlParametersInterface.
   *
   * @return CurlConverter
   */
  public function setCurlParameters(CurlParametersInterface $curlParameters): CurlConverter
  {
    $this->curlParameters = $curlParameters;
    return $this;
  }


}