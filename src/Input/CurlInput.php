<?php

namespace Hashbangcode\CurlConverter\Input;

use Hashbangcode\CurlConverter\CurlParametersInterface;
use Hashbangcode\CurlConverter\CurlParameters;

/**
 * Class CurlInput.
 *
 * @package Hashbangcode\CurlConverter\Input
 */
class CurlInput implements InputInterface
{

  /**
   * {@inheritdoc}
   */
  public function extract(string $data): CurlParametersInterface
  {
    $data = trim($data);
    if (strpos($data, 'curl ') !== 0) {
      // @todo : refactor this into a method and a throwable error
      die('not a curl command');
    }

    $curlParameters = new CurlParameters();

    // Remove the "curl" command part from the front.
    $data = substr(trim($data), 4);
    $data = str_replace(" \\\n", '', $data);

    $splitCommand = array_filter(preg_split('/\s--?/U', $data));

    foreach ($splitCommand as $flag) {
      // If a '-' is present in the first character then remove it.
      if (substr_compare($flag, '-', 0, 1) === 0) {
        $flag = substr($flag, 1);
      }
      $flag = trim($flag);

      if (strpos($flag, ' ') === false) {
        // If the flag contains no strings it is a single flag.
        switch (strtolower($flag)) {
          case 'insecure':
            $curlParameters->setIsInsecure(true);
            $data = str_replace($flag, '', $data);
            break;
          case 'L':
            $curlParameters->setFollowRedirects(true);
            $data = str_replace($flag, '', $data);
            break;
        }
        continue;
      }

      // Simple flag with a single, unquoted, parameter.
      $explodedFlag = explode(' ', $flag);
      if ($explodedFlag[0] !== '') {
        switch ($explodedFlag[0]) {
          case 'u':
          case 'user':
            $credentials = explode(':', $explodedFlag[1]);
            $curlParameters->setUsername($credentials[0]);
            $curlParameters->setPassword($credentials[1]);
            $data = str_replace($explodedFlag[0] . ' ' . $explodedFlag[1], '', $data);
            break;
          case 'X':
          case 'request':
            $curlParameters->setHttpVerb(strtoupper($explodedFlag[1]));
            $data = str_replace($explodedFlag[0] . ' ' . $explodedFlag[1], '', $data);
            break;
          case 'url':
            $curlParameters->setUrl($explodedFlag[1]);
            $data = str_replace($explodedFlag[0] . ' ' . $explodedFlag[1], '', $data);
            break;
          case 'proxy':
            $curlParameters->setProxy($explodedFlag[1]);
            $data = str_replace($explodedFlag[0] . ' ' . $explodedFlag[1], '', $data);
            break;
        }

        if (preg_match('/X(.*)/', $explodedFlag[0], $httpVerb) === 1) {
          if ($httpVerb[1] !== '') {
            $curlParameters->setHttpVerb(strtoupper($httpVerb[1]));
            $data = str_replace($httpVerb[0], '', $data);
          }
          $explodedFlag[0] = str_replace($httpVerb[0], '', $explodedFlag[0]);
        }

        foreach (str_split($explodedFlag[0]) as $arg) {
          switch ($arg) {
            case 's':
              // Silent.
              $data = str_replace($explodedFlag[0], '', $data);
              break;
            case 'S':
              // Show error.
              $data = str_replace($explodedFlag[0], '', $data);
              break;
            case 'L':
              // Location.
              $curlParameters->setFollowRedirects(true);
              $data = str_replace($explodedFlag[0], '', $data);
              break;
            case 'I':
              // Head.
              if ($curlParameters->getHttpVerb() === 'GET') {
                $curlParameters->setHttpVerb('HEAD');
              }
              $data = str_replace($explodedFlag[0], '', $data);
            break;
          }
        }
      }

      if (substr_compare($flag, '"', -1) === 0) {
        // Flag contents has double quotes.
        preg_match('/"([^"]+)\"/U', $flag, $flagContents);
        $flagKey = trim(str_replace($flagContents[0], '', $flag));
        $flagContents = $flagContents[1];

        $this->extractQuotedParams($curlParameters, $flagKey, $flagContents);

        $data = str_replace($flagKey . ' "' . $flagContents . '"', '', $data);
        continue;
      }

      if (substr_compare($flag, '\'', -1) === 0) {
        // Flag contents has single quotes.
        preg_match('/\'([^\']+)\'/U', $flag, $flagContents);
        $flagKey = trim(str_replace($flagContents[0], '', $flag));
        $flagContents = $flagContents[1];

        $this->extractQuotedParams($curlParameters, $flagKey, $flagContents);

        $data = str_replace($flagKey . ' \'' . $flagContents . '\'', '', $data);
      }
    }

    if (empty($curlParameters->getUrl())) {
      // Find url.
      $data = trim($data);
      preg_match_all('/(?:[\'|"]?)https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)(?:[\'|"]?)/im', $data, $url);

      if (isset($url[0])) {
        $url = str_replace(['"', "'"], '', $url[0][0]);
        $curlParameters->setUrl($url);
      }
    }

    return $curlParameters;
  }

  /**
   * Extract the arguments from a quoted parameter.
   *
   * @param CurlParametersInterface $curlParameters
   *   The current object that implements CurlParametersInterface.
   * @param string $flagKey
   *   The flag key.
   * @param string $flagContents
   *   The flag contents.
   */
  protected function extractQuotedParams($curlParameters, $flagKey, $flagContents) {
    switch ($flagKey) {
      case 'H':
      case 'header':
      case 'headers':
        $curlParameters->addHeader($flagContents);
        break;
      case 'u':
      case 'user':
        $credentials = explode(':', $flagContents);
        $curlParameters->setUsername($credentials[0]);
        $curlParameters->setPassword($credentials[1]);
        break;
      case 'data':
      case 'data-ascii':
      case 'd':
      case 'data-raw':
      case 'data-urlencode':
      case 'data-binary':
        if ($curlParameters->getHttpVerb() === 'GET') {
          $curlParameters->setHttpVerb('POST');
        }
        $curlParameters->setData($flagContents);
        break;
      case 'proxy':
        $curlParameters->setProxy($flagContents);
        break;
      case 'url':
        $curlParameters->setUrl($flagContents);
        break;
    }
  }

}