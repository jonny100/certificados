<?php
namespace App\Application\ToolsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;


function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}



class TaggedServiceListerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $listers_name = 'application_tools.tagged_service_lister';

        // always first check if the primary service is defined
        if (!$container->has($listers_name)) {
            return;
        }
        
        $definition = $container->findDefinition($listers_name);
        $tagged_services = $container->findTaggedServiceIds($listers_name);
        
        foreach($tagged_services as $lid => $ltags) {
            $lister_def = $container->findDefinition($lid);

            $tags = $definition->getTags();
            $tags = array_keys($tags);
            
            foreach($tags as $tag) {
                if(startsWith($tag, 'tag')) {
                    $tagvalue = $definition->getTag($tag);
                    $taggedServices = $container->findTaggedServiceIds($tagvalue);
                    foreach ($taggedServices as $id => $tags) {
                        $definition->addMethodCall(
                            'addServiceId',
                            array($tagvalue, $id)
                        );
                    }
                }
            }   
        }
    }
}
