<?php

require_once './config/db.php';

class UserDetailsModel
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::connection();
    }

    /**
     * 🔹 Create a new user
     */
    public function create($userId)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO user_details (user_id)
            VALUES (:user_id)
        ");

        $stmt->execute([
            'user_id' => $userId,
        ]);

        // Get inserted ID
        $id = $this->pdo->lastInsertId();

        // Fetch and return inserted user data
        return $this->find($id);
    }

    /**
     * 🔍 Read one user by ID
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, user_id, created_at FROM user_details WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 📄 Read all users
     */
    public function all()
    {
        $stmt = $this->pdo->query("SELECT id, user_name, email,mobile,role_id  created_at FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ✏️ Update a user by ID
     */
    public function update($id, $name, $email)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET user_name = :user_name, email = :email WHERE id = :id
        ");

        return $stmt->execute([
            'user_name' => $name,
            'email' => $email,
            'id' => $id
        ]);
    }

    /**
     * 🔒 Update password
     */
    public function updatePassword($id, $newPassword)
    {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = :password WHERE id = :id");

        return $stmt->execute([
            'password' => $hashed,
            'id' => $id
        ]);
    }

    /**
     * ❌ Delete a user by ID
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * 🔍 Find user by email (e.g. login)
     */
    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
