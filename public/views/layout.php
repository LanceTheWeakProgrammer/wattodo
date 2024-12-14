<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body>
    <header class="bg-main text-white p-2">
        <div class="d-flex justify-content-between align-items-center px-3">
            <div class="d-flex align-items-center">
                <img src="/public/images/WattoDo-white.svg" alt="WattoDo Logo" width="70" height="70">
                <h1 class="h3 mb-0">WattoDo</h1>
            </div>
            <div>
                <button class="btn btn-light btn-sm d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <button id="logoutButton" class="btn btn-light btn-sm">
                    <i class="fa-solid fa-right-from-bracket me-1"></i>
                    <span>
                        Logout
                    </span>
                </button>
            </div>
        </div>
    </header>

    <div class="d-flex">
        <nav class="col-lg-2 bg-light sidebar p-2 vh-100 d-none d-lg-block" style="position: sticky; top: 0; overflow-y: auto; z-index: 1020;">
            <ul class="nav flex-column">
                <?php
                $currentPage = basename($_SERVER['REQUEST_URI'], ".php"); 
                ?>
                <li class="nav-item my-1 mx-2">
                    <a class="nav-link <?= ($currentPage === 'dashboard') ? 'active' : '' ?>" href="/dashboard"><i class="fa-solid fa-home me-2"></i> Home</a>
                </li>
                <li class="nav-item my-1 mx-2">
                    <a class="nav-link <?= ($currentPage === 'tasks') ? 'active' : '' ?>" href="/tasks"><i class="fa-solid fa-tasks me-2"></i> Tasks</a>
                </li>
                <li class="nav-item my-1 mx-2">
                    <a class="nav-link <?= ($currentPage === 'profile') ? 'active' : '' ?>" href="/profile"><i class="fa-solid fa-user me-2"></i> Profile</a>
                </li>
            </ul>
        </nav>

        <div class="offcanvas offcanvas-start bg-light d-lg-none" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <nav class="sidebar">
                    <ul class="nav flex-column">
                        <li class="nav-item my-1 mx-2">
                            <a class="nav-link <?= ($currentPage === 'dashboard') ? 'active' : '' ?>" href="/dashboard"><i class="fa-solid fa-home me-2"></i> Home</a>
                        </li>
                        <li class="nav-item my-1 mx-2">
                            <a class="nav-link <?= ($currentPage === 'tasks') ? 'active' : '' ?>" href="/tasks"><i class="fa-solid fa-tasks me-2"></i> Tasks</a>
                        </li>
                        <li class="nav-item my-1 mx-2">
                            <a class="nav-link <?= ($currentPage === 'profile') ? 'active' : '' ?>" href="/profile"><i class="fa-solid fa-user me-2"></i> Profile</a>
                        </li>
                        <li class="nav-item my-1 mx-2">
                            <a class="nav-link <?= ($currentPage === 'settings') ? 'active' : '' ?>" href="/settings"><i class="fa-solid fa-cog me-2"></i> Settings</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <main class="col-lg-10 mx-auto p-4">
            <?php echo $content; ?>
        </main>
    </div>
</body>
</html>
