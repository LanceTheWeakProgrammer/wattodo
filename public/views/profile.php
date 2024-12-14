<?php 
    $pageTitle = "Profile";
    ob_start(); 
?>
<h2>Account Center</h2>
<p class="text-muted">Manage your personal information and view account details.</p>
<hr class="divider">

<div class="card shadow-sm">
    <div class="card-body">
        <div id="profile">
            <div class="mb-3">
                <label class="form-label text-muted">Full Name</label>
                <p id="name">Loading...</p>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted">Username</label>
                <p id="username">Loading...</p>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted">Bio</label>
                <p class="text-muted mb-0" id="bio">Loading...</p>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted">Phone Number</label>
                <p id="phoneNumber">Loading...</p>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted">Address</label>
                <p id="address">Loading...</p>
            </div>
        </div>
    </div>
</div>

<script src="/public/js/profile.js"></script>
<?php
    $content = ob_get_clean(); 
    include 'layout.php';
?>
