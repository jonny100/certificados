<?php

namespace App\Application\ToolsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use App\Application\ToolsBundle\DependencyInjection\Compiler\TaggedServiceListerCompilerPass;


class ApplicationToolsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TaggedServiceListerCompilerPass());
    }
	

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'ToolsBundle';
    }	
	
	
}


?>
