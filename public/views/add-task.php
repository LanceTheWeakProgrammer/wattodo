<?php 
    $pageTitle = "Create a Task";
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
        <li class="breadcrumb-item"><a href="" class="text-decoration-none text-main">Create a Task</a></li>
    </ol>
</nav>

<h2>Create a Task</h2>
<p class="text-muted">Fill in the details below to create a new task.</p>
<hr class="divider">

<div class="container-fluid mt-4">
    <form id="createTaskForm">
        <div class="mb-3">
            <label for="taskTitle" class="form-label">Task Title</label>
            <input type="text" id="taskTitle" class="form-control" placeholder="Enter task title" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Does this task have a deadline?</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hasDeadline" id="hasDeadlineYes" value="yes">
                    <label class="form-check-label" for="hasDeadlineYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hasDeadline" id="hasDeadlineNo" value="no" checked>
                    <label class="form-check-label" for="hasDeadlineNo">No</label>
                </div>
            </div>
        </div>

        <div id="dateFields" class="row" style="display: none;">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" id="startDate" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" id="endDate" class="form-control">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="taskDescription" class="form-label">Task Description</label>
            <textarea id="taskDescription" class="form-control" rows="3" placeholder="Enter task description" required></textarea>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-main me-2">Create Task</button>
            <button type="button" id="cancelTaskButton" class="btn btn-secondary">Cancel</button>
        </div>
    </form>
</div>

<script src="/public/js/task.js"></script>
<?php
    $content = ob_get_clean(); 
    include 'layout.php';
?>
