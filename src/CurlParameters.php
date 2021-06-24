<?php

namespace Hashbangcode\CurlConverter;

/**
 * Class CurlParameters.
 *
 * A common class to store and retrieve curl parameters.
 *
 * @package Hashbangcode\CurlConverter
 */
class CurlParameters implements CurlParametersInterface
{
  /**
   * The url being used in the call.
   * @var string
   */
  protected $url;

  /**
   * An array of headers that are to be used in the call.
   * @var array
   */
  protected $headers = [];

  /**
   * The data being sent to the request.
   * @var string
   */
  protected $data;

  /**
   * The HTTP verb being used.
   * @var string
   */
  protected $httpVerb = 'GET';

  /**
   * The is this an insecure command?
   * @var boolean
   */
  protected $isInsecure = false;

  /**
   * The username of the request.
   * @var string
   */
  protected $username;

  /**
   * The password of the request.
   * @var string
   */
  protected $password;

  /**
   * The follow redirects.
   * @var boolean
   */
  protected $followRedirects = false;

  /**
   * {@inheritdoc}
   */
  public function getUrl(): ?string
  {
    return $this->url;
  }

  /**
   * {@inheritdoc}
   */
  public function setUrl(string $url): CurlParametersInterface
  {
    $this->url = $url;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHeaders(): array
  {
    return $this->headers;
  }

  /**
   * {@inheritdoc}
   */
  public function setHeaders(array $headers): CurlParametersInterface
  {
    $this->headers = $headers;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getData(): ?string
  {
    return $this->data;
  }

  /**
   * {@inheritdoc}
   */
  public function setData(string $data): CurlParametersInterface
  {
    $this->data = $data;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasData(): bool
  {
    return (strlen($this->getData()) > 0) ?  TRUE : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getHttpVerb(): string
  {
    return $this->httpVerb;
  }

  /**
   * {@inheritdoc}
   */
  public function setHttpVerb(string $httpVerb): CurlParametersInterface
  {
    $this->httpVerb = $httpVerb;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isInsecure(): bool
  {
    return $this->isInsecure;
  }

  /**
   * {@inheritdoc}
   */
  public function setIsInsecure(bool $isInsecure): CurlParametersInterface
  {
    $this->isInsecure = $isInsecure;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUsername(): ?string
  {
    return $this->username;
  }

  /**
   * {@inheritdoc}
   */
  public function setUsername(string $username): CurlParametersInterface
  {
    $this->username = $username;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPassword(): ?string
  {
    return $this->password;
  }

  /**
   * {@inheritdoc}
   */
  public function setPassword(string $password): CurlParametersInterface
  {
    $this->password = $password;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function areCredentialsSet(): bool
  {
    if (!empty($this->getUsername()) && !empty($this->getPassword())) {
      return true;
    }
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function setFollowRedirects(bool $followRedirects): CurlParametersInterface
  {
    $this->followRedirects = $followRedirects;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function followRedirects() : bool
  {
    return $this->followRedirects;
  }
}