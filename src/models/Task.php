<?php
declare(strict_types=1);

namespace Models;

use PDO;

class Task {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = new PDO('sqlite:' . __DIR__ . '/../data/tasks.db');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create table if it doesn't exist (automatic initialization)
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL
        )');
    }

    public function all(): array {
        $stmt = $this->pdo->query('SELECT * FROM tasks');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $title): array {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title) VALUES (:title)");
        $stmt->execute([':title' => $title]);
        return [
            'id' => (int)$this->pdo->lastInsertId(),
            'title' => $title
        ];
    }

    public function findById(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update(int $id, string $title): ?array {
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = :title WHERE id = :id");
        $stmt->execute([':id' => $id, ':title' => $title]);
        
        if ($stmt->rowCount() > 0) {
            return $this->findById($id);
        }
        return null;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}