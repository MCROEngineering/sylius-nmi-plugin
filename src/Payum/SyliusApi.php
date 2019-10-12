<?php

declare(strict_types=1);

namespace MCRO\SyliusNMIPlugin\Payum;

final class SyliusApi
{
  /**
   * @var string|null
   */
  private $apiKey;

  /** @var string|null */
  private $tokenization;

  /** @var string|null */
  private $username;

  /** @var string|null */
  private $password;

  public function __construct(
    ?string $apiKey,
    ?string $tokenization,
    ?string $username,
    ?string $password
  ) {
    $this->apiKey = $apiKey;
    $this->tokenization = $tokenization;
    $this->username = $username;
    $this->password = $password;
  }

  /**
   * @return string
   */
  public function getApiKey(): ?string
  {
    return $this->apiKey;
  }

  /**
   * @return string|null
   */
  public function getTokenization(): ?string
  {
    return $this->tokenization;
  }

  /**
   * @return string|null
   */
  public function getUsername(): ?string
  {
    return $this->username;
  }

  /**
   * @return string|null
   */
  public function getPassword(): ?string
  {
    return $this->password;
  }
}
