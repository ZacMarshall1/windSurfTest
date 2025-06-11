<?php

if (!isset($_SESSION)) {
    session_start();
}

/**
 * Set a flash message to be displayed on the next page load.
 *
 * @param string $message The message to display.
 * @param string $type    The type of the message (e.g., 'success', 'danger', 'info').
 */
function set_flash_message($message, $type = 'info') {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Display the flash message if one is set.
 */
function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message']['message'];
        $type = $_SESSION['flash_message']['type'];
        echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>";
        echo htmlspecialchars($message);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';

        // Clear the message after displaying it
        unset($_SESSION['flash_message']);
    }
}
