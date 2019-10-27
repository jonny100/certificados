<?php
namespace App\Application\ToolsBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PreviewFileTypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'file';
    }
    
    public static function getExtendedTypes(): iterable
    {
        return [FileType::class];
    }	
	
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array(
            'link_cb',
            'link_label_cb',
            'thumb_cb',
            ));
    }
    
    /**
     * Pass the format to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $thumb_url = null;
        $link_url = null;
        $link_label = null;
        
        if (array_key_exists('thumb_cb', $options)) {
            $parentData = $form->getParent()->getData();
            $callback = $options['thumb_cb'];

            if (null !== $parentData && is_callable($callback)) {
#                $accessor = PropertyAccess::createPropertyAccessor();
#                $thumb_url = $accessor->getValue($parentData, $options['thumb_path']);
                $thumb_url = $callback($parentData);
            }
        }
        if (array_key_exists('link_cb', $options)) {
            $parentData = $form->getParent()->getData();
            $callback = $options['link_cb'];

            if (null !== $parentData && is_callable($callback)) {
                $link_url = $callback($parentData);
            }
        }
        if (array_key_exists('link_label_cb', $options)) {
            $parentData = $form->getParent()->getData();
            $callback = $options['link_label_cb'];

            if (null !== $parentData && is_callable($callback)) {
                $link_label = $callback($parentData);
            }
        }
        
        // set an "image_url" variable that will be available when
        // rendering this field
        $view->vars['thumb_url'] = $thumb_url;
        $view->vars['link_url'] = $link_url;
        $view->vars['link_label'] = $link_label;
    }
}
