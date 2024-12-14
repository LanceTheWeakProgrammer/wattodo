$(document).ready(function () {
    let userId = null;

    function fetchUserId() {
        return $.ajax({
            url: '/getUserId',
            type: 'GET',
            success: function (response) {
                const result = JSON.parse(response);
                userId = result.user_id;

                if (!userId) {
                    alert('User ID not found. Redirecting to login.');
                    window.location.href = '/login';
                }
            },
            error: function () {
                alert('Error fetching user ID. Redirecting to login.');
                window.location.href = '/login';
            }
        });
    }

    function fetchCategories() {
        if (!userId) {
            alert('User ID is not available. Please log in again.');
            return;
        }

        $.ajax({
            url: '/categories',
            type: 'GET',
            data: { user_id: userId },
            success: function (response) {
                const result = JSON.parse(response);

                if (result.status === 'success') {
                    render(result.data);
                } else {
                    alert('Failed to load categories: ' + result.message);
                }
            },
            error: function () {
                alert('An error occurred while fetching categories.');
            }
        });
    }

    function render(categories) {
        const categoryContainer = $('.category-data');
        categoryContainer.empty();
    
        categories.forEach(category => {
            const tasks = category.tasks || [];

            const pinnedTasks = tasks.filter(task => task.is_pinned === 1);
            const normalTasks = tasks.filter(task => task.is_pinned === 0);

            const pinnedTasksAccordion = pinnedTasks.length
                ? pinnedTasks.map(task => accordion(task)).join('')
                : '<p class="text-muted">No pinned tasks available</p>';

            const normalTasksAccordion = normalTasks.length
                ? normalTasks.map(task => accordion(task)).join('')
                : '<p class="text-muted">No tasks available</p>';
    
            const categoryCard = `
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-main text-white d-flex justify-content-between align-items-center">
                            <span>${category.name}</span>
                            <button class="btn btn-dark btn-sm add-task-button" data-category-id="${category.id}">
                                <i class="fa-solid fa-plus me-1"></i> Create Task
                            </button>
                        </div>
                        <div class="card-body">
                            <p class="card-text">${category.description}</p>

                            <div class="d-flex align-items-center mb-2 ms-2" style="font-size: 13px;">
                                <i class="fa-solid fa-thumbtack me-2 text-success"></i>
                                <h6 class="text-success mt-1">Pinned Tasks</h6>
                            </div>
                            <div class="accordion mb-4" id="pinnedAccordion${category.id}">
                                ${pinnedTasksAccordion}
                            </div>

                            <hr class="divider">
                            <div class="accordion" id="normalAccordion${category.id}">
                                ${normalTasksAccordion}
                            </div>
                        </div>
                    </div>
                </div>
            `;
    
            categoryContainer.append(categoryCard);
        });

        $('.add-task-button').on('click', function () {
            const categoryId = $(this).data('category-id');
            window.location.href = `/add-task?category_id=${categoryId}&user_id=${userId}`;
        });
    
        $('.task-options-button').on('click', function () {
            const taskId = $(this).data('task-id');
            const categoryId = $(this).data('category-id');
            const buttonPosition = $(this).offset();
    
            const task = categories.find(cat => cat.id === categoryId).tasks.find(t => t.id === taskId);
            popover(taskId, categoryId, buttonPosition, task.is_pinned);
        });
    
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.custom-popover, .task-options-button').length) {
                $('.custom-popover').remove();
            }
        });
    
        $('.complete-task-button').on('click', function () {
            const taskId = $(this).data('task-id');
            markTaskAsComplete(taskId);
        });
    }

    function accordion(task) {
        const isCompleted = task.status === 'Completed';
    
        return `
            <div class="accordion-item">
                <h2 class="accordion-header" id="task${task.id}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTask${task.id}" aria-expanded="false" aria-controls="collapseTask${task.id}">
                        ${task.title}
                    </button>
                </h2>
                <div id="collapseTask${task.id}" class="accordion-collapse collapse" aria-labelledby="task${task.id}">
                    <div class="accordion-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div></div>
                            <button class="btn task-options-button" data-task-id="${task.id}" data-category-id="${task.category_id}">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <p>${task.description}</p>
                        </div>
                        <span class="badge bg-${mapStatus(task.status)} mb-2">${task.status}</span>
                        <div class="d-flex justify-content-between mt-3">
                            ${!isCompleted
                                ? `<button class="btn btn-success btn-sm complete-task-button" data-task-id="${task.id}" style="font-size: 13px;">
                                        <i class="fa-solid fa-check"></i> Mark as Complete
                                   </button>`
                                : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function popover(taskId, categoryId, position, isPinned) {
        const existingPopover = $(`.custom-popover[data-task-id="${taskId}"]`);
        if (existingPopover.length) {
            existingPopover.remove();
            return;
        }
    
        $('.custom-popover').remove();
    
        const isSmallScreen = window.innerWidth <= 768;
        const popoverLeft = isSmallScreen ? (position.left || 0) - 130 : position.left || 0;
        const popoverTop = position.top ? position.top + 35 : 100;

        const pinText = isPinned === 1 ? 'Unpin task' : 'Pin task';
        const pinIcon = isPinned === 1 ? 'fa-thumbtack-slash' : 'fa-thumbtack';
    
        const popoverHtml = `
            <div class="custom-popover" data-task-id="${taskId}" style="position: absolute; top: ${popoverTop}px; left: ${popoverLeft}px; z-index: 1050; background: white; border: 1px solid #ccc; padding: 10px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
                <div class="d-flex flex-column">
                    <button class="btn btn-link text-start task-delete-option" data-bs-toggle="modal" data-bs-target="#deleteTaskModal" data-task-id="${taskId}" style="color: var(--bs-secondary); font-size: 13px; text-decoration: none;">
                        <i class="fa-solid fa-trash me-2"></i> Delete task
                    </button>
                    <button class="btn btn-link text-start task-edit-option" data-task-id="${taskId}" data-category-id="${categoryId}" style="color: var(--bs-secondary); font-size: 13px; text-decoration: none;">
                        <i class="fa-solid fa-pencil me-2"></i> Edit task
                    </button>
                    <button class="btn btn-link text-start task-pin-option" data-task-id="${taskId}" style="color: var(--bs-secondary); font-size: 13px; text-decoration: none;">
                        <i class="fa-solid ${pinIcon} me-2"></i> ${pinText}
                    </button>
                </div>
            </div>
        `;
    
        $('body').append(popoverHtml);
    
        $('.task-delete-option').on('click', function () {
            const taskId = $(this).data('task-id');
            $('#deleteTaskModal').modal('show'); 
            $('#confirmDeleteTaskButton').data('task-id', taskId); 
        });
    
        $('.task-edit-option').on('click', function () {
            const taskId = $(this).data('task-id');
            const categoryId = $(this).data('category-id');
            editTask(taskId, categoryId);
        });
    
        $('.task-pin-option').on('click', function () {
            const taskId = $(this).data('task-id');
            pinTask(taskId);
        });
    }
    
    $('#addCategoryForm').on('submit', function (event) {
        event.preventDefault();

        const name = $('#categoryName').val();
        const description = $('#categoryDescription').val();

        if (name && description) {
            $.ajax({
                url: '/categories/store',
                type: 'POST',
                data: { name: name, description: description, user_id: userId },
                success: function (response) {
                    const result = JSON.parse(response);

                    if (result.status === 'success') {
                        alert('Category added successfully!');
                        fetchCategories();
                        $('#addCategoryModal').modal('hide');
                    } else {
                        alert('Failed to add category: ' + result.message);
                    }
                },
                error: function () {
                    alert('An error occurred while adding the category.');
                }
            });
        } else {
            alert('All fields are required.');
        }
    });

    $('#confirmDeleteTaskButton').on('click', function () {
        const taskId = $(this).data('task-id');
        deleteTask(taskId);
    });

    function deleteTask(taskId) {
        $.ajax({
            url: '/tasks/delete',
            type: 'POST',
            data: { task_id: taskId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Task deleted successfully!');
                    fetchCategories();
                    $('#deleteTaskModal').modal('hide');
                } else {
                    alert('Failed to delete task: ' + result.message);
                }
            },
            error: function () {
                alert('An error occurred while deleting the task.');
            }
        });
    }

    function editTask(taskId, categoryId) {
        window.location.href = `/edit-task?task_id=${taskId}&category_id=${categoryId}&user_id=${userId}`;
    }

    function pinTask(taskId) {
        $.ajax({
            url: '/tasks/pin',
            type: 'POST',
            data: { task_id: taskId },
            success: function (response) {
                const result = JSON.parse(response);
    
                if (result.status === 'success') {
                    alert('Task pin status toggled successfully!');
    
                    const pinButton = $(`.task-pin-option[data-task-id="${taskId}"]`);
                    const isPinned = result.is_pinned; 

                    if (isPinned === 1) {
                        pinButton.find('i').removeClass('fa-thumbtack').addClass('fa-thumbtack-slash');
                        pinButton.text(' Unpin task').prepend('<i class="fa-solid fa-thumbtack-slash me-2"></i>');
                    } else {
                        pinButton.find('i').removeClass('fa-thumbtack-slash').addClass('fa-thumbtack');
                        pinButton.text(' Pin task').prepend('<i class="fa-solid fa-thumbtack me-2"></i>');
                    }

                    $(`.custom-popover[data-task-id="${taskId}"]`).remove();

                    fetchCategories();
                } else {
                    alert('Failed to toggle pin status: ' + result.message);
                }
            },
            error: function () {
                alert('An error occurred while toggling pin status.');
            }
        });
    }
    
    function markTaskAsComplete(taskId) {
        $.ajax({
            url: '/tasks/complete',
            type: 'POST',
            data: { task_id: taskId },
            success: function (response) {
                const result = JSON.parse(response);

                if (result.status === 'success') {
                    alert('Task marked as completed successfully!');
                    fetchCategories();
                } else {
                    alert('Failed to mark task as completed: ' + result.message);
                }
            },
            error: function () {
                alert('An error occurred while marking the task as completed.');
            }
        });
    }

    function mapStatus(status) {
        switch (status) {
            case 'Pending': return 'secondary';
            case 'Completed': return 'success';
            default: return 'secondary';
        }
    }

    fetchUserId().then(() => {
        fetchCategories();
    });
});
