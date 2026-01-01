<?php
declare(strict_types=1);

namespace App;

use Models\Task;

class TaskController {
    private Task $taskModel;

    public function __construct() {
        $this->taskModel = new Task();
    }

    public function list(): void {
        header('Content-Type: application/json');
        echo json_encode($this->taskModel->all());
    }

    public function create(): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!is_array($data) || !isset($data['title'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. Title is required.']);
            return;
        }
        
        $task = $this->taskModel->create($data['title']);

        http_response_code(201);
        echo json_encode($task);
    }

    public function show(int $id): void {
        header('Content-Type: application/json');
        $task = $this->taskModel->findById($id);
        
        if ($task === null) {
            http_response_code(404);
            echo json_encode(['error' => 'Task not found']);
            return;
        }
        
        echo json_encode($task);
    }

    public function update(int $id): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!is_array($data) || !isset($data['title'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input. Title is required.']);
            return;
        }
        
        $task = $this->taskModel->update($id, $data['title']);
        
        if ($task === null) {
            http_response_code(404);
            echo json_encode(['error' => 'Task not found']);
            return;
        }
        
        echo json_encode($task);
    }

    public function delete(int $id): void {
        header('Content-Type: application/json');
        
        if ($this->taskModel->delete($id)) {
            http_response_code(204);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Task not found']);
        }
    }
}