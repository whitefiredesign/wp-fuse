<?php namespace Fuse; ?>

<div class="wrap fuse">
    <h1>Fuse Form</h1>

    <div id="form-tabs">
        <ul>
            <li><a href="#avail-forms">Available Forms</a></li>
            <li><a href="#sub-logs">Form Submission Logs</a></li>
        </ul>

        <div id="avail-forms">
            <?php include_once(__DIR__ . '/admin-available-forms.php'); ?>
        </div>

        <div id="sub-logs">
            <?php include_once(__DIR__ . '/admin-submission-logs.php'); ?>
        </div>
    </div>
</div>