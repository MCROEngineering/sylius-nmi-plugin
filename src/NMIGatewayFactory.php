<?php

declare(strict_types=1);

namespace MCRO\SyliusNMIPlugin;

use MCRO\SyliusNMIPlugin\Payum\Action\CaptureAction;
use MCRO\SyliusNMIPlugin\Payum\Action\ConvertPaymentAction;
use MCRO\SyliusNMIPlugin\Payum\Action\ObtainTokenAction;
use MCRO\SyliusNMIPlugin\Payum\Action\StatusAction;
use MCRO\SyliusNMIPlugin\Payum\SyliusApi;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class NMIGatewayFactory extends GatewayFactory
{
  protected function populateConfig(ArrayObject $config): void
  {
    $config->defaults([
      'payum.factory_name' => 'nmi_payment',
      'payum.factory_title' => 'NMI Payment',
    ]);

    $config['payum.api'] = function (ArrayObject $config) {
      return new SyliusApi(
        $config['api_key'],
        $config['tokenization'],
        $config['username'],
        $config['password']
      );
    };
  }
}
