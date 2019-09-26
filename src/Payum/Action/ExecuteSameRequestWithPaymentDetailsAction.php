<?php

namespace MCRO\SyliusNMIPlugin\Payum\Action;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Generic;
use Sylius\Component\Payment\Model\PaymentInterface;

final class ExecuteSameRequestWithPaymentDetailsAction extends GatewayAwareAction
{
  /**
   * {@inheritdoc}
   *
   * @param Generic $request
   */
  public function execute($request): void
  {
    RequestNotSupportedException::assertSupports($this, $request);

    /** @var PaymentInterface $payment */
    $payment = $request->getFirstModel();
    $details = ArrayObject::ensureArrayObject($payment->getDetails());

    try {
      $request->setModel($details);

      $this->gateway->execute($request);
    } finally {
//      $payment->setDetails((array) $details);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function supports($request): bool
  {
    return
      $request instanceof Generic &&
      $request->getModel() instanceof PaymentInterface
      ;
  }
}
