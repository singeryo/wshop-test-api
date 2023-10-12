<?php

/**
 * @desc To output the response in XML
 * @author Paul Doelle, 29/03/15
 */
class XmlView extends ApiView
{
    /**
     * @param $content
     * @return string
     */
    public function render($content): string
    {
        header('Content-Type: application/xml; charset=utf8');
        $xml = $this->generateValidXmlFromArray($content);
        echo $xml;

        return $xml;
    }

    /**
     * @param $array
     * @param $nodeName
     * @return string
     */
    private function generateXmlFromArray($array, $nodeName): string
    {
        if (!(is_array($array) || is_object($array))) {
            return htmlspecialchars($array, ENT_QUOTES) . "\n";
        }

        $xml = '';
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = $nodeName;
            }

            $xml .= '<' . $key . '>' . "\n" . $this->generateXmlFromArray($value, $nodeName) . '</' . $key . '>' . "\n";
        }

        return $xml;
    }

    /**
     * @param $array
     * @param $nodeBlock
     * @param $nodeName
     * @return string
     */
    private function generateValidXmlFromArray($array, $nodeBlock = 'nodes', $nodeName = 'node'): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $xml .= '<' . $nodeBlock . '>' . "\n";
        $xml .= $this->generateXmlFromArray($array, $nodeName);
        $xml .= '</' . $nodeBlock . '>' . "\n";
        return $xml;
    }
}
