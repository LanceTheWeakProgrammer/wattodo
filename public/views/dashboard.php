<?php 
    $pageTitle = "Dashboard";
    ob_start(); 
?>
<h2>Dashboard</h2>
<p class="text-muted">Manage tasks, track progress, and focus on what matters today.</p>
<hr class="divider">

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-main text-white">
                Task Overview
            </div>
            <div class="card-body">
                <p class="text-muted">
                    <span id="completed-percentage"></span> Completed | 
                    <span id="pending-percentage"></span> Pending
                </p>
                <div class="progress mt-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-main text-white">
                Date today
            </div>
            <div class="card-body text-center">
                <p id="current-date" class="fw-bold mb-2">Loading Date...</p>
                <p id="current-time" class="fw-bold text-muted">Loading Time...</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-main text-white">
                Today's Focus
            </div>
            <div class="card-body">
                <ul class="list-group" id="tasks-today-list">
                </ul>
                <p class="mt-3 text-muted text-center">Focus on completing today's priority tasks!</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-main text-white">
                Pinned Tasks
            </div>
            <div class="card-body">
                <ul class="list-group" id="pinned-tasks-list">
                </ul>
                <p class="mt-3 text-muted text-center">Keep your most important tasks at the top!</p>
            </div>
        </div>
    </div>
</div>

<script src="/public/js/dashboard.js"></script>
<?php
    $content = ob_get_clean(); 
    include 'layout.php';
?>
