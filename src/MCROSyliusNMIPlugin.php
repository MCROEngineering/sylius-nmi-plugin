<?php

declare(strict_types=1);

namespace MCRO\SyliusNMIPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MCROSyliusNMIPlugin extends Bundle
{
    use SyliusPluginTrait;
}
