<?php
/**
 * @desc Parse the request data
 * @author Paul Doelle
 */

class Request
{
    // URL indexes => http://{0}/{1}/{2}
    const URL_ROUTE_INDEX = 1;
    const URL_ITEM_INDEX = 2;

    // URL elements in array delimited by '/' excluding parameters
    public array $urlElements;
    // HTTP verb
    public string $verb;
    // URL parameters. reserved variables = format, public_key, public_hash
    public array $parameters;
    // Body - parsed complex object request
    public $body;
    // Content-Type of the request
    public string $requestFormat;
    // Output requested Content-Type.
    // Based on &format URL parameter, defaults based on request format, else defaults to 'json'
    public string $outputFormat;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->verb = $_SERVER['REQUEST_METHOD'];
        $this->urlElements = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);
        $this->outputFormat = 'json';
        $this->parseURLParams();
        $this->parseBody();

        if (!empty($this->parameters['format'])) {
            $this->outputFormat = $this->parameters['format'];
        }
    }

    /**
     * @return void
     */
    private function parseURLParams()
    {
        $this->parameters = [];

        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $this->parameters);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function parseBody()
    {
        $body = file_get_contents('php://input');
        $this->requestFormat = false;

        if (empty($_SERVER['CONTENT_TYPE']) && strlen($body) > 0) {
            throw new RequestException('There was no Content-Type set in the request.', 400);
        }

        if (strlen($body) == 0) {
            return;
        }

        $this->requestFormat = $_SERVER['CONTENT_TYPE'];

        switch ($this->requestFormat) {
            case 'application/json':
            {
                $this->body = json_decode($body);
                if (!$this->body) {
                    throw new RequestException(
                        'The request content was invalid and could not be parsed successfully as JSON.',
                        400
                    );
                }

                $this->outputFormat = 'json';
                break;
            }

            case 'application/xml':
            {
                $this->body = simplexml_load_string($body);
                if (!$this->body) {
                    throw new RequestException(
                        'The request body was invalid and could not be parsed successfully as XML.',
                        400
                    );
                }

                $this->outputFormat = 'xml';
                break;
            }

            default:
            {
                throw new RequestException(
                    "Unsupported request body Content-Type of '" . $_SERVER['CONTENT_TYPE'] . "'.",
                    400
                );
            }
        }
    }
}
