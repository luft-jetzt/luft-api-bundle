<?php declare(strict_types=1);

namespace Caldera\LuftApiBundle;

use Caldera\LuftApiBundle\DependencyInjection\CalderaLuftApiExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LuftApiBundle extends Bundle
{
    public function getContainerExtension(): Extension
    {
        return new CalderaLuftApiExtension();
    }
}
