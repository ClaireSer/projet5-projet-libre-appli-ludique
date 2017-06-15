<?php
namespace UserBundle\Form\Type;

use Symfony\Component\Form\DataTransformerInterface;

class StringToArrayTransformer implements DataTransformerInterface
{
    /**
     * Transforms an array to a string. 
     * POSSIBLE LOSS OF DATA
     *
     * @return string
     */
    public function transform($array)
    {
        if (empty($array)) {
            return false;
        }
        return $array[key($array)];
    }

    /**
     * Transforms a string to an array.
     *
     * @param  string $string
     *
     * @return array
     */
    public function reverseTransform($string)
    {
        return array($string);
    }
}
