<?php

use Joren\UploadsSync\SyncCommand;

/*
 * Plugin Name:       Uploads Sync
 * Plugin URI:        https://github.com/jorenrothman/uploads-sync
 * Description:       A CLI plugin to sync the uploads folder with the database.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      8.0
 * Author:            Joren Rothman
 * Author URI:        https://github.com/jorenrothman
 * License:           MIT
 * License URI:       https://opensource.org/license/mit/
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Stop if WP-CLI is not present.
if (!defined('WP_CLI')) {
    return;
}

// Load autoload.php
require_once __DIR__ . '/vendor/autoload.php';

// Register the command.
WP_CLI::add_command('uploads-sync', SyncCommand::class);