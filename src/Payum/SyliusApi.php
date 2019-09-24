<?php

declare(strict_types=1);

namespace MCRO\SyliusNMIPlugin\Payum;

final class SyliusApi
{
  /**
   * @var string|null
   */
  private $apiKey;

  /** @var string */
  private $username;

  /** @var string */
  private $password;

  public function __construct(?string $apiKey, string $username, string $password)
  {
    $this->apiKey = $apiKey;
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
   * @return string
   */
  public function getUsername(): string
  {
    return $this->username;
  }

  /**
   * @return string
   */
  public function getPassword(): string
  {
    return $this->password;
  }
}
