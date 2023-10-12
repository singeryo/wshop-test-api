<?php

/**
 * @desc To output the response in JSON
 * @author Paul Doelle, 29/03/15
 */
class JsonView extends ApiView
{
    /**
     * @param $content
     * @return bool|string
     */
    public function render($content)
    {
        header('Content-Type: application/json; charset=utf8');
        $json = json_encode($content, JSON_INVALID_UTF8_SUBSTITUTE);
        echo $json;

        return $json;
    }
}
