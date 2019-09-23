<?php

declare(strict_types=1);

namespace MCRO\SyliusNMIPlugin\Payum\Action;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Stripe\Request\Api\CreateCharge;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;

final class DirectPostAction implements ActionInterface
{
  /** @var Client */
  private $client;

  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  public function execute($request): void
  {
    RequestNotSupportedException::assertSupports($this, $request);

    /** @var SyliusPaymentInterface $payment */
    $payment = $request->getModel();



//    var_dump($request); exit;
    try {
      $response = $this->client->request('POST', 'https://secure.networkmerchants.com/api/transact.php', [
        'body' => json_encode([
          'price' => $payment->getAmount(),
          'currency' => $payment->getCurrencyCode(),
          'api_key' => $this->api->getApiKey(),
        ]),
      ]);

    } catch (RequestException $exception) {
      $response = $exception->getResponse();
    } finally {
      $payment->setDetails(['status' => $response->getStatusCode()]);
    }
  }

  public function supports($request): bool
  {
    return
      $request instanceof CreateCharge &&
      $request->getModel() instanceof \ArrayObject;
  }
}
