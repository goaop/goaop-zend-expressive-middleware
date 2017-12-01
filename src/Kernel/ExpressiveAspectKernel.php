<?php

namespace Go\Zend\Expressive\Kernel;

use Go\Core\AspectContainer;
use Go\Core\AspectKernel;

/**
 * @package Go\Zend\Expressive\Kernel
 */
class ExpressiveAspectKernel extends AspectKernel
{
    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param AspectContainer $container
     *
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
    }
}
