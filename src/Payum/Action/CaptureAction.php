<?php

declare(strict_types=1);

namespace MCRO\SyliusNMIPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Stripe\Request\Api\CreateCharge;
use Payum\Stripe\Request\Api\ObtainToken;

final class CaptureAction implements ActionInterface, GatewayAwareInterface
{
  use GatewayAwareTrait;

  public function execute($request): void
  {
    RequestNotSupportedException::assertSupports($this, $request);

    $model = ArrayObject::ensureArrayObject($request->getModel());

    if ($model['status']) {
      return;
    }

    if ($model['customer']) {
    } else {
      if (false == $model['card']) {
        $obtainToken = new ObtainToken($request->getToken());
        $obtainToken->setModel($request->getFirstModel());

        $this->gateway->execute($obtainToken);
      }
    }

    $this->gateway->execute(new CreateCharge($request->getFirstModel()));
  }

  public function supports($request): bool
  {
    return
      $request instanceof Capture &&
      $request->getModel() instanceof \ArrayAccess;
  }
}
