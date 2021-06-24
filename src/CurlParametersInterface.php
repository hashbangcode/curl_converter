<?php

namespace Hashbangcode\CurlConverter;

/**
 * Interface CurlParametersInterface.
 *
 * A common interface to store and retrieve curl parameters.
 *
 * @package Hashbangcode\CurlConverter
 */
interface CurlParametersInterface {

  /**
   * Get the URL of the request.
   *
   * @return null|string
   */
  public function getUrl(): ?string;

  /**
   * Set the URL of the request.
   *
   * @param string $url
   *   The URL.
   *
   * @return CurlParametersInterface
   */
  public function setUrl(string $url): CurlParametersInterface;

  /**
   * Get the headers of the request.
   *
   * @return array
   *   An array of headers to add to the request.
   */
  public function getHeaders(): array;

  /**
   * Set the headers for the request.
   *
   * @param array $headers
   *   The headers to set.
   *
   * @return CurlParametersInterface
   */
  public function setHeaders(array $headers): CurlParametersInterface;

  /**
   * Get the data payload of the request.
   *
   * @return null|string
   *   The payload (or null if not set).
   */
  public function getData(): ?string;

  /**
   * Set the data payload of the request.
   *
   * @param string $data
   *   The payload to set.
   *
   * @return CurlParametersInterface
   */
  public function setData(string $data): CurlParametersInterface;

  /**
   * Is data present in the object.
   *
   * @return bool
   *   True if data is present.
   */
  public function hasData(): bool;

  /**
   * Get the HTTP verb of the request.
   *
   * @return string
   *   The HTTP verb.
   */
  public function getHttpVerb(): string;

  /**
   * Set the HTTP verb of the request.
   *
   * @param string $httpVerb
   *   The HTTP verb.
   *
   * @return CurlParametersInterface
   */
  public function setHttpVerb(string $httpVerb): CurlParametersInterface;

  /**
   * Is the request insecure?
   *
   * @return bool
   *   True if the request is insecure.
   */
  public function isInsecure(): bool;

  /**
   * Set the status of the insecure request.
   *
   * Used when sending a request to a HTTPS endpoint that doesn't not have valid a SSL certificate.
   *
   * @param bool $isInsecure
   *   Set to true to set the request to be insecure.
   *
   * @return CurlParametersInterface
   */
  public function setIsInsecure(bool $isInsecure): CurlParametersInterface;

  /**
   * Get the username to add to the request.
   *
   * @return null|string
   *   The username.
   */
  public function getUsername(): ?string;

  /**
   * Set the username to add to the request.
   *
   * @param string $username
   *   The username.
   *
   * @return CurlParametersInterface
   */
  public function setUsername(string $username): CurlParametersInterface;

  /**
   * Get the password to add to the request.
   *
   * @return null|string
   *   The password.
   */
  public function getPassword(): ?string;

  /**
   * Set the password to add to the request.
   *
   * @param string $password
   *   The password.
   *
   * @return CurlParametersInterface
   */
  public function setPassword(string $password): CurlParametersInterface;

  /**
   * Are the credential parameters set?
   *
   * @return bool
   *   True if the credentials are set.
   */
  public function areCredentialsSet(): bool;

  /**
   * Set a flag to follow through with redirects.
   *
   * @param bool $followRedirects
   *   True to get the request to follow redirects.
   *
   * @return CurlParametersInterface
   */
  public function setFollowRedirects(bool $followRedirects): CurlParametersInterface;

  /**
   * A flag to follow redirects in the command.
   *
   * @return bool
   *   True if redirects are to be followed.
   */
  public function followRedirects() : bool;
}