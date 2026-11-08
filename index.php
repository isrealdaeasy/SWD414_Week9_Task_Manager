<?php

$tasksFile = __DIR__ . "/tasks.json";

if (!file_exists($tasksFile)) {
    file_put_contents($tasksFile, "[]");
}

$tasks = json_decode(file_get_contents($tasksFile), true);

if (!is_array($tasks)) {
    $tasks = [];
}

function clean($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TaskFlow | Task Management</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <header>
        <div class="logo">TASKFLOW</div>

        <h1>Manage Your Tasks</h1>

        <p>Organise your work, stay productive and achieve your goals.</p>
    </header>

    <?php if (isset($_GET["success"])): ?>
        <div class="success">
            Task added successfully!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET["error"])): ?>
        <div class="error">
            Please enter a valid task title.
        </div>
    <?php endif; ?>

    <section class="form-card">

        <h2>Add a New Task</h2>

        <form action="add_task.php" method="POST">

            <input
                type="text"
                name="title"
                placeholder="What do you need to do?"
                maxlength="150"
                required
            >

            <textarea
                name="description"
                placeholder="Add task description (optional)"
                maxlength="1000"
            ></textarea>

            <button type="submit">+ Add Task</button>

        </form>

    </section>

    <section class="tasks-section">

        <div class="section-heading">
            <h2>My Tasks</h2>

            <span class="count">
                <?php echo count($tasks); ?> Tasks
            </span>
        </div>

        <?php if (count($tasks) > 0): ?>

            <?php foreach (array_reverse($tasks) as $task): ?>

                <div class="task-card">

                    <div>
                        <h3><?php echo clean($task["title"] ?? ""); ?></h3>

                        <p>
                            <?php
                            echo clean(
                                $task["description"] ?? "No description provided."
                            );
                            ?>
                        </p>

                        <small>
                            Added:
                            <?php echo clean($task["created_at"] ?? ""); ?>
                        </small>
                    </div>

                    <span class="status">Pending</span>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="empty">

                <div class="empty-icon">📋</div>

                <h3>No tasks yet</h3>

                <p>Your tasks will appear here after you add them.</p>

            </div>

        <?php endif; ?>

    </section>

    <footer>
        <p>SWD414 Backend Development II</p>
        <p>Week 9 Practical Project | Task Management Application</p>
    </footer>

</div>

</body>
</html>