<?php

/**
 * @desc Stores controller
 * @author Oliver Singery
 */
class StoresController extends ApiController
{
    private $model;
    private string $modelName;
    public array $acceptedParams = ['name', 'address'];

    /**
     * @throws Exception
     */
    public function __construct()
    {
        preg_match("/(.+)Controller$/", get_class($this), $match);

        $this->modelName = $match[1] . "Model";

        if (!class_exists($this->modelName)) {
            throw new InvalidRequestException('Model does not exist.', 500);
        }

        $this->model = new $this->modelName();
    }

    /*
     @uri	/stores
     @verb	GET
     @desc	Get a list of stores
     */
    /*
     @uri	/stores/{id}
     @verb	GET
     @desc	Get one store
     */
    /**
     * @param Request $request
     * @return mixed
     * @throws InvalidRequestException
     */
    public function getAction(Request $request)
    {
        if (empty($request->urlElements[Request::URL_ITEM_INDEX])) {
            $filters = $this->getFilters($request);
            $sort = $this->getSorting($request);

            return $this->model->getStores($filters, $sort);
        }

        $storeId = (int)$request->urlElements[Request::URL_ITEM_INDEX];

        if (!$storeId) {
            throw new InvalidRequestException('Invalid Store ID.', 400);
        }

        return $this->model->getStore($storeId);
    }

    /*
     @uri	/stores
     @verb	POST
     @desc	Create one store
     */
    /**
     * @param $request
     * @return mixed
     * @throws Exception
     */
    public function postAction($request)
    {
        $this->model = Helper::cast($request->body->store, $this->modelName);

        if (!$this->model->name && $this->model->address) {
            throw new InvalidRequestException('Invalid or missing store object in request.', 400);
        }

        return $this->model->createStore();
    }


    /*
     * @uri	/stores
     * @verb PUT
     * @desc Update one store
     */
    /**
     *
     * @param $request
     * @return mixed
     * @throws Exception
     */
    public function putAction($request)
    {
        $this->model = Helper::cast($request->body->store, $this->modelName);

        if (!$this->model->id) {
            throw new InvalidRequestException('Invalid or missing store object in request.', 400);
        }

        return $this->model->updateStore();
    }

    /*
     * @uri	/stores/{id}
     * @verb DELETE
     * @desc Delete one store
     */
    /**
     * @param $request
     * @return mixed
     * @throws Exception
     */
    public function deleteAction($request)
    {
        if (!$request->urlElements[Request::URL_ITEM_INDEX]) {
            throw new InvalidRequestException('Missing Store ID.', 400);
        }

        $storeId = (int)$request->urlElements[Request::URL_ITEM_INDEX];

        if (!$storeId) {
            throw new InvalidRequestException('Invalid Store ID.', 400);
        }

        return $this->model->deleteStore($storeId);
    }
}
