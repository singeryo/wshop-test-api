<?php

/**
 * @desc Controller master class - for any global properties and functions
 * @author Paul Doelle
 */
class ApiController
{
    public array $acceptedParams;

    /**
     * Whitelist all accepted filter keys in $acceptedParams
     *
     * @param Request $request
     * @return array
     */
    public function getFilters(Request $request): array
    {
        return isset($request->parameters['filter']) && is_array($request->parameters['filter'])
            ? array_filter(
                $request->parameters['filter'],
                fn($item, $key) => in_array($key, $this->acceptedParams),
                ARRAY_FILTER_USE_BOTH
            )
            : [];
    }

    /**
     * Whitelist all accepted sort keys in $acceptedParams
     *
     * @param Request $request
     * @return mixed|string
     */
    public function getSorting(Request $request)
    {
        return (isset($request->parameters['sortBy'])
            && in_array($request->parameters['sortBy'], $this->acceptedParams))
            ? $request->parameters['sortBy']
            : '';
    }
}
