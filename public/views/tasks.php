<?php 
    $pageTitle = "Dashboard";
    ob_start(); 
?>
<h2>Manage Tasks</h2>
<p class="text-muted">Organize and track your categories and tasks effortlessly.</p>
<hr class="divider">

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Categories</h3>
        <button id="addCategoryButton" class="btn btn-main" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa-solid fa-plus me-2"></i>Add Category
        </button>
    </div>
        <div class="category-data row">
        </div>
</div>

<!-- Add a category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addCategoryForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" id="categoryName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Category Description</label>
                        <textarea id="categoryDescription" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-main">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete a task -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTaskModalLabel">Delete Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center px-2">
                    <i class="fa-solid fa-triangle-exclamation fa-3x me-4"></i>
                    <p class="mt-3">Are you sure you want to delete this task? This action cannot be undone.</p>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTaskButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="/public/js/category.js"></script>
<?php
    $content = ob_get_clean(); 
    include 'layout.php';
?>
