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

    protected $fields = ['*'];
    protected $params = [];

    protected $joins = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        // Auto-detect table name from child class
        if (!$this->table) {
            $class = get_class($this);
            $this->table = strtolower(preg_replace('/Model$/', '', $class)) . 's';
        }
    }

    public function join($table, $first, $operator, $second)
    {
        $this->joins[] = "INNER JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second)
    {
        $this->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function enableSoftDeletes()
    {
        $this->softDeleteEnabled = true;
        return $this;
    }

    public function select(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function all()
    {
        return $this->get();
    }

    public function find(array $conditions)
    {
        foreach ($conditions as $key => $value) {
            $this->where($key, '=', $value);
        }
        return $this->first();
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
        $data['updated_at'] = date('Y-m-d H:i:s');

        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $where = implode(' AND ', array_map(fn($k) => "$k = :where_$k", array_keys($conditions)));

        $params = $data;
        foreach ($conditions as $k => $v) {
            $params["where_$k"] = $v;
        }

        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $set WHERE $where");
        $success = $stmt->execute($params);

        return $success ? $this->find($conditions) : false;
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

    // public function where($field, $operator, $value = null)
    // {
    //     if ($value === null) {
    //         $value = $operator;
    //         $operator = '=';
    //     }

    //     $param = "param_" . count($this->bindings);
    //     $this->where[] = "$field $operator :$param";
    //     $this->bindings[$param] = $value;
    //     return $this;
    // }
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
        foreach ($values as $val) {
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

        $columns = implode(', ', $this->fields);

        // Prepare WHERE clause
        $whereSql = '';
        if (!empty($this->where)) {
            $whereSql = 'WHERE ' . implode(' AND ', $this->where);
        }

        // Soft delete condition
        if ($this->softDeleteEnabled) {
            $deletedCondition = "`deleted_at` IS NULL";
            $whereSql = $whereSql ? "$whereSql AND $deletedCondition" : "WHERE $deletedCondition";
        }

        // Total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} $whereSql";
        $countStmt = $this->pdo->prepare($countSql);
        $countStmt->execute($this->bindings);
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Final data query
        $sql = "SELECT $columns FROM {$this->table} $whereSql {$this->order} LIMIT $offset, $perPage";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->fields = ['*'];
        $this->resetQuery();

        return [
            'data' => $data,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'lastPage' => ceil($total / $perPage),
        ];
    }

    // public function get()
    // {
    //     $columns = implode(', ', $this->fields);
    //     $whereClause = '';

    //     if (!empty($this->where)) {
    //         $whereClause = ' WHERE ' . implode(' AND ', $this->where);
    //     }

    //     if ($this->softDeleteEnabled) {
    //         $whereClause .= (empty($whereClause) ? ' WHERE ' : ' AND ') . "deleted_at IS NULL";
    //     }

    //     $sql = "SELECT $columns FROM {$this->table} $whereClause{$this->order}{$this->limit}";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute($this->bindings);

    //     $this->fields = ['*'];
    //     $this->resetQuery();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    public function get()
    {
        $columns = implode(', ', $this->fields);
        $whereClause = '';
        $joinClause = '';

        if (!empty($this->joins)) {
            $joinClause = ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $whereClause = ' WHERE ' . implode(' AND ', $this->where);
        }

        if ($this->softDeleteEnabled) {
            $whereClause .= (empty($whereClause) ? ' WHERE ' : ' AND ') . "{$this->table}.deleted_at IS NULL";
        }

        $sql = "SELECT $columns FROM {$this->table}{$joinClause}{$whereClause}{$this->order}{$this->limit}";

        // 🔍 Debug
        // echo "SQL: $sql<br>";
        // echo "Bindings: ";
        // print_r($this->bindings);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);

        $this->fields = ['*'];
        $this->resetQuery();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function orWhere($field, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $param = "param_" . count($this->bindings);
        $condition = "$field $operator :$param";

        if (empty($this->where)) {
            $this->where[] = $condition;
        } else {
            $this->where[] = "OR $condition";
        }

        $this->bindings[$param] = $value;
        return $this;
    }

    public function whereNull($field)
    {
        $this->where[] = "$field IS NULL";
        return $this;
    }

    public function whereNotNull($field)
    {
        $this->where[] = "$field IS NOT NULL";
        return $this;
    }
    public function first()
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    protected function resetQuery()
    {
        $this->where = [];
        $this->bindings = [];
        $this->order = '';
        $this->limit = '';
        $this->joins = [];
    }
}
