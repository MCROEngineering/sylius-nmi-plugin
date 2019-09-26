<?php

namespace MCRO\SyliusNMIPlugin\Payum\Action;

use MCRO\SyliusNMIPlugin\Payum\SyliusApi;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\RenderTemplate;
use Payum\Stripe\Request\Api\ObtainToken;

class ObtainTokenAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
  use GatewayAwareTrait;

  /** @var SyliusApi */
  private $api;

  /**
   * @var string
   */
  protected $templateName;

  /**
   * @param string $templateName
   */
  public function __construct($templateName)
  {
    $this->templateName = '@' . $templateName;
  }


  public function execute($request): void
  {
    /** @var $request ObtainToken */
    RequestNotSupportedException::assertSupports($this, $request);

    $model = ArrayObject::ensureArrayObject($request->getFirstModel());
    $payment = $request->getFirstModel();

    if ($model['card']) {
      throw new LogicException('The token has already been set.');
    }

    $getHttpRequest = new GetHttpRequest();
    $this->gateway->execute($getHttpRequest);
    if ($getHttpRequest->method == 'POST' && isset($getHttpRequest->request['payment_token'])) {
      $payment->card = $getHttpRequest->request['payment_token'];
      $model['card'] = $getHttpRequest->request['payment_token'];

      return;
    }

    $this->gateway->execute($renderTemplate = new RenderTemplate($this->templateName, array(
      'model' => $request->getFirstModel(),
      'publishable_key' => $this->api->getApiKey(),
      'actionUrl' => $request->getToken() ? $request->getToken()->getTargetUrl() : null,
    )));

    throw new HttpResponse($renderTemplate->getResult());
  }

  public function supports($request): bool
  {
    return
      $request instanceof ObtainToken &&
      $request->getModel() instanceof \ArrayAccess;
  }

  public function setApi($api): void
  {
    if (!$api instanceof SyliusApi) {
      throw new UnsupportedApiException('Not supported. Expected an instance of ' . SyliusApi::class);
    }

    $this->api = $api;
  }
}
