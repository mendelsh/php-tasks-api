<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Router;
use App\TaskController;

$router = new Router();

// list all tasks
$router->get('/tasks', [TaskController::class, 'list']);

// create a new task
$router->post('/tasks', [TaskController::class, 'create']);

// get a single task by ID
$router->get('/tasks/:id', [TaskController::class, 'show']);

// update a task by ID
$router->put('/tasks/:id', [TaskController::class, 'update']);
$router->patch('/tasks/:id', [TaskController::class, 'update']);

// delete a task by ID
$router->delete('/tasks/:id', [TaskController::class, 'delete']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
