<?php

namespace Hashbangcode\CurlConverter\Output;

use Hashbangcode\CurlConverter\CurlParametersInterface;

/**
 * Class PhpOutput.
 *
 * @package Hashbangcode\CurlConverter\Output
 */
class PhpGuzzleOutput implements OutputInterface
{

  /**
   * {@inheritdoc}
   */
  public function render(CurlParametersInterface $curlParameters): string
  {
    $output = '';

    $output .= '<?php' . PHP_EOL;

    $output .= 'use GuzzleHttp\Client;' . PHP_EOL;
    $output .= 'use GuzzleHttp\Exception\ClientException;' . PHP_EOL;
    $output .= 'use GuzzleHttp\Exception\ConnectException;' . PHP_EOL;
    $output .= 'use GuzzleHttp\Exception\RequestException;' . PHP_EOL;
    $output .= 'use GuzzleHttp\Exception\ServerException;' . PHP_EOL;
    $output .= 'use GuzzleHttp\Psr7\Request;' . PHP_EOL;
    $output .= 'use GuzzleHttp\RequestOptions;' . PHP_EOL;
    $output .= PHP_EOL;
    $output .= '$client = new Client();' . PHP_EOL;
    $output .= '$request = new Request(\'GET\', \'' . $curlParameters->getUrl() . '\');' . PHP_EOL;
    $output .= PHP_EOL;
    $output .= 'try {' . PHP_EOL;
    $output .= '  /** @var \GuzzleHttp\Psr7\Response $response */' . PHP_EOL;
    $output .= '  $response = $client->send($request, [RequestOptions::ALLOW_REDIRECTS => false]);';
    $output .= '} catch (ClientException $e) {' . PHP_EOL;
    $output .= '   $response = $e->getResponse();' . PHP_EOL;
    $output .= '} catch (ServerException $e) {' . PHP_EOL;
    $output .= '  $response = $e->getResponse();' . PHP_EOL;
    $output .= '} catch (ConnectException $e) {' . PHP_EOL;
    $output .= '  // Unable to connect to endpoint due to DNS or other error.' . PHP_EOL;
    $output .= '}' . PHP_EOL;
    $output .= PHP_EOL;
    $output .= 'echo $response->getStatusCode();';
    $output .= 'echo $response->getHeaders();';
    $output .= 'echo $response->getBody()->getSize() ?: 0;';
    $output .= 'echo $response->getBody()->getContents();';

    return $output;
  }

}