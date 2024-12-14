<?php 
    $pageTitle = "Edit a Task";
    ob_start(); 
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="/dashboard">
                <i class="fa-solid fa-house text-dark"></i>
            </a>
        </li>
        <li class="breadcrumb-item"><a href="/tasks" class="text-decoration-none text-dark">Tasks</a></li>
        <li class="breadcrumb-item"><span class="text-decoration-none text-main">Edit a Task</span></li>
    </ol>
</nav>

<h2>Edit a Task</h2>
<p class="text-muted">Fill in the details below to edit a task.</p>
<hr class="divider">

<div class="container-fluid mt-4">
<form id="editTaskForm" method="POST" action="/tasks/update">
    <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']) ?>">
    <input type="hidden" name="category_id" value="<?= htmlspecialchars($task['category_id']) ?>">

    <div class="mb-3">
        <label for="taskTitle" class="form-label">Task Title</label>
        <input type="text" id="taskTitle" name="title" class="form-control" 
               value="<?= htmlspecialchars($task['title']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="taskDescription" class="form-label">Task Description</label>
        <textarea id="taskDescription" name="description" class="form-control" 
                  rows="3" required><?= htmlspecialchars($task['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Does this task have a deadline?</label>
        <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hasDeadline" id="hasDeadlineYes" value="yes"
                        <?= !empty($task['start_date']) && !empty($task['end_date']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="hasDeadlineYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hasDeadline" id="hasDeadlineNo" value="no"
                        <?= empty($task['start_date']) && empty($task['end_date']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="hasDeadlineNo">No</label>
                </div>
            </div>
        </div>

        <div id="dateFields" class="row" style="<?= empty($task['start_date']) && empty($task['end_date']) ? 'display: none;' : '' ?>">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" id="startDate" name="start_date" class="form-control" 
                        value="<?= htmlspecialchars($task['start_date'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" id="endDate" name="end_date" class="form-control" 
                        value="<?= htmlspecialchars($task['end_date'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-main me-2">Save Changes</button>
            <button type="button" id="cancelTaskButton" class="btn btn-secondary">Cancel</button>
        </div>
    </form>
</div>

<script src="/public/js/task.js"></script>
<?php
    $content = ob_get_clean(); 
    include 'layout.php';
?>
