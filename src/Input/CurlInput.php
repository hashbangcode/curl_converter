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

    $headers = [];

    $curlParameters = new CurlParameters();

    // Remove the curl command from the front.
    $data = substr(trim($data), 5);

    // Match parameters.
    $regex = '';
    $regex .= '(-[a-zA-Z\-]+? \'?.*[\s]\'?)|'; // Single letter flags with values.
    $regex .= '(--[a-zA-Z\-]+ \'?.*?\'?[\s])|'; // Multi letter flags with values.
    $regex .= '("?[a-z]+:\/\/.*?"+?)|'; // Address matching with double quotes.
    $regex .= '(\'?[a-z]+:\/\/.*?\'+?)|'; // Address matching with single quotes.
    $regex .= '(--[a-zA-Z\-]+)|'; // Multi letter flags with no value.
    $regex .= '(-[a-aA-Z]+)'; // Single letter flags with no value.
    preg_match_all('/' . $regex . '/', $data, $parameters);

    $parameters = $parameters[0];
    $parameterCount = count($parameters);
    for ($i = 0; $i < $parameterCount; ++$i) {
      if (preg_match('/(?:--?)([A-Za-z\-]+)(?:\s?)(.*)?/', $parameters[$i], $flagMatch) === 1) {
        $flag = $flagMatch[1];
        $contents = trim($flagMatch[2]);
        switch ($flag) {
          case 'url':
            $curlParameters->setUrl($contents);
            break;
          case 'H':
          case 'header':
          case 'headers':
            $contents = trim(str_replace(["'", '\\'], '', $contents));
            $headers[] = $contents;
            break;
          case 'X':
            $curlParameters->setHttpVerb(strtoupper($contents));
            break;
          case 'u':
          case 'user':
            $credentials = explode(':', $contents);
            $curlParameters->setUsername($credentials[0]);
            $curlParameters->setPassword($credentials[1]);
            break;
          case 'data':
          case 'data-ascii':
          case 'd':
          case 'data-raw':
          case 'data-urlencode':
          case 'data-binary':
            $curlParameters->setHttpVerb('POST');
            $curlParameters->setData($contents);
            break;
          case 'insecure':
            $curlParameters->setIsInsecure(true);
            break;
          case 'L':
            $curlParameters->setFollowRedirects(true);
            break;
        }
      }
    }

    if (empty($curlParameters->getUrl())) {
      // Find url.
      preg_match_all('/(?:[\'|"]?)https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)(?:[\'|"]?)/im', $data, $url);

      if (isset($url[0])) {
        $url = str_replace(['"', "'"], '', $url[0][0]);
        $curlParameters->setUrl($url);
      }
    }

    $curlParameters->setHeaders($headers);

    return $curlParameters;
  }

}