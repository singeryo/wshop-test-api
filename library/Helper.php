<?php

/**
 * @desc Helper class - generic functions for reuse
 * @author Paul Doelle
 */
class Helper
{
    /**
     * convert an object into a specific class
     *
     * @param $instance
     * @param $className
     * @return mixed
     */
    public static function cast($instance, $className)
    {
        return unserialize(
            sprintf(
                'O:%d:"%s"%s',
                strlen($className),
                $className,
                strstr(strstr(serialize($instance), '"'), ':')
            )
        );
    }
}
