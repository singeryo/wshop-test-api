<?php

/**
 * @desc Stores model
 * @author Paul Doelle
 */
class StoresModel extends ApiModel
{
    public string $id;
    public string $name = '';
    public string $address = '';

    /**
     * @param array $filters
     * @param string $sort
     * @return array
     * @throws ModelException
     * @throws Exception
     */
    public function getStores(array $filters = [], string $sort = ''): array
    {
        $queryString = 'SELECT * FROM stores ';
        $whereClauses = [];
        $queryParams = [];

        foreach ($filters as $key => $filter) {
            $whereClauses[] = "$key LIKE :$key";
            // Just need to sanitize where values, because all keys are already filtered out in controller
            $queryParams[$key] = '%' . $filter . '%';
        }

        foreach ($whereClauses as $key => $whereClause) {
            $whereString = $key == 0 ? "WHERE $whereClause " : "AND $whereClause ";
            $queryString .= $whereString;
        }

        if (strlen($sort) > 0) {
            $queryString .= "ORDER BY $sort";
        }

        $pdo = DB::get()->prepare($queryString);

        $pdo->execute($queryParams);
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'StoresModel');

        if (count($result) === 0) {
            throw new ModelException('No stores found.', 204);
        }

        return $result;
    }

    /**
     * @param $storeId
     * @return array
     * @throws Exception
     */
    public function getStore($storeId): array
    {
        $pdo = DB::get()->prepare("SELECT * FROM stores WHERE id = :id");
        $pdo->execute(['id' => $storeId]);
        $result = $pdo->fetchAll(PDO::FETCH_CLASS, 'StoresModel');

        if (!$result) {
            throw new ModelException('No store found.', 204);
        }

        return $result;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function createStore()
    {
        $pdo = DB::get()->prepare('INSERT INTO stores (name, address) VALUES (:name, :address)');
        $pdo->execute([
            ':name' => $this->name,
            ':address' => $this->address
        ]);

        if ($pdo->rowCount() == 0) {
            throw new ModelException('No store was created.', 500);
        }

        $this->id = DB::lastInsertId();

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function updateStore()
    {
        $pdo = DB::get()->prepare('UPDATE stores SET name = :name, address = :address WHERE id = :id');
        $pdo->execute(array(
            ':id' => $this->id,
            ':name' => $this->name,
            ':address' => $this->address
        ));

        if ($pdo->rowCount() == 0) {
            throw new ModelException('No store was updated.', 200);
        }

        return $this;
    }

    /**
     * @param $storeId
     * @return $this
     * @throws Exception
     */
    public function deleteStore($storeId)
    {
        $pdo = DB::get()->prepare("DELETE FROM stores WHERE id = :id");
        $pdo->execute([':id' => $storeId]);

        if ($pdo->rowCount() == 0) {
            throw new ModelException('No store was deleted.', 400);
        }

        $this->id = $storeId;
        return $this;
    }
}
