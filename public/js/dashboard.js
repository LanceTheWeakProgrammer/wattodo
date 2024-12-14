$(document).ready(function () {
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString(undefined, options);
        const timeString = now.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit', second: '2-digit' });

        $('#current-date').text(dateString);
        $('#current-time').text(timeString);
    }

    updateDateTime();

    setInterval(updateDateTime, 1000);

    function fetchDashboardStats() {
        $.ajax({
            url: '/dashboard/stats',
            method: 'GET',
            success: function (response) {
                const data = JSON.parse(response);

                if (data.status === 'success') {
                    const completed = data.data.task_stats.Completed || 0;
                    const pending = data.data.task_stats.Pending || 0;
                    const total = completed + pending;

                    const completedPercentage = total > 0 ? Math.round((completed / total) * 100) : 0;
                    const pendingPercentage = 100 - completedPercentage;

                    $('#completed-percentage').text(`${completedPercentage}%`);
                    $('#pending-percentage').text(`${pendingPercentage}%`);

                    $('.progress-bar.bg-success')
                        .css('width', `${completedPercentage}%`)
                        .text(`${completedPercentage}%`);
                    $('.progress-bar.bg-secondary')
                        .css('width', `${pendingPercentage}%`)
                        .text(`${pendingPercentage}%`);

                    const tasksToday = data.data.tasks_today;
                    const tasksTodayList = tasksToday.map(task => `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${task.title}
                            <span class="badge bg-primary rounded-pill">${task.status}</span>
                        </li>
                    `).join('');
                    $('#tasks-today-list').html(tasksTodayList);

                    const pinnedTasks = data.data.pinned_tasks;
                    const pinnedTasksList = pinnedTasks.map(task => `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${task.title}
                            <span class="badge bg-info rounded-pill">Pinned</span>
                        </li>
                    `).join('');
                    $('#pinned-tasks-list').html(pinnedTasksList);
                } else {
                    console.error('Failed to fetch dashboard stats:', data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching dashboard stats:', error);
            }
        });
    }

    fetchDashboardStats();
});
