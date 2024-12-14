$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const taskId = urlParams.get('task_id');
    const categoryId = urlParams.get('category_id');

    $('input[name="hasDeadline"]').on('change', function () {
        const hasDeadline = $(this).val() === 'yes';
        $('#dateFields').toggle(hasDeadline);

        if (!hasDeadline) {
            $('#startDate').val('');
            $('#endDate').val('');
        }
    });

    $('#editTaskForm, #createTaskForm').on('submit', function (event) {
        event.preventDefault();

        const title = $('#taskTitle').val();
        const description = $('#taskDescription').val().replace(/\n/g, '<br>');
        const hasDeadline = $('input[name="hasDeadline"]:checked').val() === 'yes';
        let startDate = $('#startDate').val();
        let endDate = $('#endDate').val();

        if (!title || !description) {
            alert('Title and description are required.');
            return;
        }

        if (hasDeadline) {
            if (!startDate || !endDate) {
                alert('Both start date and end date are required if the task has a deadline.');
                return;
            }
            if (new Date(startDate) > new Date(endDate)) {
                alert('Start date cannot be later than end date.');
                return;
            }
        } else {
            startDate = null;
            endDate = null;
        }

        const requestData = {
            title: title,
            description: description,
            category_id: categoryId,
            start_date: startDate || null,
            end_date: endDate || null,
        };

        if (taskId) requestData.task_id = taskId;

        const actionUrl = taskId ? '/tasks/update' : '/tasks/store';

        $.ajax({
            url: actionUrl,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(requestData),
            success: function (response) {
                const result = JSON.parse(response);

                if (result.status === 'success') {
                    alert(taskId ? 'Task updated successfully!' : 'Task created successfully!');
                    window.location.href = '/tasks';
                } else {
                    alert('Failed to save task: ' + result.message);
                }
            },
            error: function () {
                alert('An error occurred while saving the task.');
            },
        });
    });

    $('#cancelTaskButton').on('click', function () {
        window.location.href = '/tasks';
    });

    if (taskId) {
        $.ajax({
            url: `/tasks/get?task_id=${taskId}`,
            type: 'GET',
            success: function (response) {
                const result = JSON.parse(response);

                if (result.status === 'success') {
                    const task = result.data;
                    $('#taskTitle').val(task.title);
                    $('#taskDescription').val(task.description.replace(/<br>/g, '\n'));
                    if (task.start_date && task.end_date) {
                        $('input[name="hasDeadline"][value="yes"]').prop('checked', true);
                        $('#startDate').val(task.start_date);
                        $('#endDate').val(task.end_date);
                        $('#dateFields').show();
                    } else {
                        $('input[name="hasDeadline"][value="no"]').prop('checked', true);
                        $('#dateFields').hide();
                    }
                } else {
                    alert('Failed to load task: ' + result.message);
                }
            },
            error: function () {
                alert('An error occurred while loading the task.');
            },
        });
    }
});
