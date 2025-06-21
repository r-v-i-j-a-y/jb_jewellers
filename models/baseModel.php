<?php

class BaseModel
{
    protected $pdo;
    protected $table;
    protected $where = [];
    protected $bindings = [];
    protected $order = '';
    protected $limit = '';
    protected $softDeleteEnabled = false;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        // Auto-detect table name from child class
        if (!$this->table) {
            $class = get_class($this);
            $this->table = strtolower(preg_replace('/Model$/', '', $class)) . 's';
        }
    }

    public function enableSoftDeletes()
    {
        $this->softDeleteEnabled = true;
        return $this;
    }

    public function all($fields = ['*'])
    {
        return $this->get($fields);
    }

    public function find(array $conditions, $fields = ['*'])
    {
        foreach ($conditions as $key => $value) {
            $this->where($key, '=', $value);
        }
        return $this->first($fields);
    }

    public function insert(array $data)
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute($data);
        $id = $this->pdo->lastInsertId();

        return $this->find(['id' => $id]);
    }

    public function update(array $data, array $conditions)
    {
        // $data['updated_at'] = date('Y-m-d H:i:s');
        // $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        // $where = implode(' AND ', array_map(fn($k) => "$k = :where_$k", array_keys($conditions)));

        // $params = $data;
        // foreach ($conditions as $k => $v) {
        //     $params["where_$k"] = $v;
        // }

        // $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $set WHERE $where");
        // return $stmt->execute($params);
        $data['updated_at'] = date('Y-m-d H:i:s');

        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $where = implode(' AND ', array_map(fn($k) => "$k = :where_$k", array_keys($conditions)));

        $params = $data;
        foreach ($conditions as $k => $v) {
            $params["where_$k"] = $v;
        }

        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $set WHERE $where");
        $success = $stmt->execute($params);

        if ($success) {
            // ✅ Return the updated data using find()
            return $this->find($conditions);
        }

        return false;
    }

    public function delete(array $conditions)
    {
        if ($this->softDeleteEnabled) {
            return $this->update(['deleted_at' => date('Y-m-d H:i:s')], $conditions);
        }

        $where = implode(' AND ', array_map(fn($k) => "$k = :$k", array_keys($conditions)));
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE $where");
        return $stmt->execute($conditions);
    }

    public function where($field, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $param = "param_" . count($this->bindings);
        $this->where[] = "$field $operator :$param";
        $this->bindings[$param] = $value;
        return $this;
    }

    public function whereIn($field, array $values)
    {
        if (empty($values))
            return $this;

        $placeholders = [];
        foreach ($values as $i => $val) {
            $key = "param_" . count($this->bindings);
            $placeholders[] = ":$key";
            $this->bindings[$key] = $val;
        }

        $this->where[] = "$field IN (" . implode(', ', $placeholders) . ")";
        return $this;
    }

    public function like($field, $value)
    {
        $param = "param_" . count($this->bindings);
        $this->where[] = "$field LIKE :$param";
        $this->bindings[$param] = "%$value%";
        return $this;
    }

    public function orderBy($field, $direction = 'ASC')
    {
        $this->order = " ORDER BY $field $direction";
        return $this;
    }

    public function limit($count)
    {
        $this->limit = " LIMIT $count";
        return $this;
    }

    public function paginate($perPage = 10, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        $this->limit = " LIMIT $offset, $perPage";
        return $this->get();
    }

    public function get($fields = ['*'])
    {
        $columns = implode(', ', $fields);
        $whereClause = '';

        if (!empty($this->where)) {
            $whereClause = ' WHERE ' . implode(' AND ', $this->where);
        }

        if ($this->softDeleteEnabled) {
            $whereClause .= (empty($whereClause) ? ' WHERE ' : ' AND ') . "deleted_at IS NULL";
        }

        $sql = "SELECT $columns FROM {$this->table} $whereClause{$this->order}{$this->limit}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);

        $this->resetQuery();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first($fields = ['*'])
    {
        return $this->limit(1)->get($fields)[0] ?? null;
    }

    protected function resetQuery()
    {
        $this->where = [];
        $this->bindings = [];
        $this->order = '';
        $this->limit = '';
    }
}
