<?php

namespace App\Application\ToolsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


class StartEndDateToArrayTransformer implements DataTransformerInterface
{
    public function transform($string)
    {
        # $string  startend_date as string. Format 2016-12
        
        $md = array('year'=>'', 'month'=>'');
        
        if (null === $string or empty($string)) {
            return $md;
        }
        $data = explode('-', $string, 2);
        $md['year'] = $data[0];
        $md['month'] = $data[1];

        return $md;
    }

    public function reverseTransform($value)
    {
        # $value startend_date as array.
        # Format array('year'=>2016, 'month'=>12)
        
        if (null === $value) {
            return;
        }

        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        if ('' === implode('', $value)) {
            return;
        }
        
        $string = $value['year'] . '-' . $value['month'];
                
        return $string;
    }
}
