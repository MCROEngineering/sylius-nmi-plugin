<?php

declare(strict_types=1);

namespace MCRO\SyliusNMIPlugin\Payum\Action;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use MCRO\SyliusNMIPlugin\Payum\SyliusApi;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Stripe\Request\Api\CreateCharge;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;

final class DirectPostAction implements ActionInterface, ApiAwareInterface
{
  /** @var Client */
  private $client;

  /** @var SyliusApi */
  private $api;

  /**
   * DirectPostAction constructor.
   * @param Client $client
   */
  public function __construct(Client $client)
  {
    $this->client = $client;
  }


  public function execute($request): void
  {
    RequestNotSupportedException::assertSupports($this, $request);

    /** @var SyliusPaymentInterface $payment */
    $payment = $request->getFirstModel();

    $model = ArrayObject::ensureArrayObject($request->getModel());


    try {

      $params = [
        'type' => 'sale',
        'amount' => number_format($payment->getAmount() / 100, 2),
        'payment_token' => $model['card'],
        'currency' => $payment->getCurrencyCode(),
      ];

      if ($this->api->getApiKey()) {
        $params['security_key'] = $this->api->getApiKey();
      } else {
        $params['username'] = $this->api->getUsername();
        $params['password'] = $this->api->getPassword();
      }

      $url = 'https://secure.networkmerchants.com/api/transact.php';

      $postURL = $url . '?' . http_build_query($params);

      $response = $this->client->request('POST', $postURL);

      $content = $response->getBody()->getContents();

      $result = $this->parseResponse($content);

      $model['status'] = (int)$result['response_code'] ?? 400;

      $request->setModel($model);

    } catch (RequestException $exception) {
      $model['status'] = 400;

      $request->setModel($model);
    }
  }

  private function parseResponse(string $content)
  {
    $content = explode('&', $content);
    $result = [];
    foreach ($content as $value) {
      $values = explode('=', $value);

      if (count($values) === 2) {
        $result[$values[0]] = $values[1];
      }
    }

    return $result;
  }

  public function supports($request): bool
  {
    return
      $request instanceof CreateCharge &&
      $request->getModel() instanceof \ArrayObject;
  }


  public function setApi($api): void
  {
    if (!$api instanceof SyliusApi) {
      throw new UnsupportedApiException('Not supported. Expected an instance of ' . SyliusApi::class);
    }

    $this->api = $api;
  }
}
