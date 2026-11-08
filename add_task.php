<?php

$tasksFile = __DIR__ . "/tasks.json";

// Accept POST requests only
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// Collect and validate form data
$title = trim($_POST["title"] ?? "");
$description = trim($_POST["description"] ?? "");

if ($title === "" || strlen($title) > 150 || strlen($description) > 1000) {
    header("Location: index.php?error=1");
    exit;
}

// Create storage file if it does not exist
if (!file_exists($tasksFile)) {
    file_put_contents($tasksFile, "[]");
}

// Open task file
$file = fopen($tasksFile, "c+");

if (!$file) {
    die("Unable to access task storage.");
}

// Lock file while reading and writing
flock($file, LOCK_EX);

$contents = stream_get_contents($file);

$tasks = json_decode($contents, true);

if (!is_array($tasks)) {
    $tasks = [];
}

// Create new task
$newTask = [
    "id" => uniqid("task_"),
    "title" => $title,
    "description" => $description,
    "created_at" => date("d M Y, h:i A")
];

// Add task to array
$tasks[] = $newTask;

// Save updated task records
$jsonData = json_encode($tasks, JSON_PRETTY_PRINT);

rewind($file);
ftruncate($file, 0);

fwrite($file, $jsonData);

fflush($file);
flock($file, LOCK_UN);
fclose($file);

// Return to homepage
header("Location: index.php?success=1");
exit;

?>