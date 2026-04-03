<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle;

use Caldera\LuftApiBundle\DependencyInjection\CalderaLuftApiExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LuftApiBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new CalderaLuftApiExtension();
    }
}
