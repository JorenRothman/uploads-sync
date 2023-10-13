<?php

use Joren\UploadsSync\CleanDBCommand;
use Joren\UploadsSync\CleanResizedCommand;
use Joren\UploadsSync\SyncCommand;

/*
 * Plugin Name:       Uploads Sync
 * Plugin URI:        https://github.com/jorenrothman/uploads-sync
 * Description:       A CLI plugin to sync the uploads folder with the database.
 * Version:           1.1.3
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

// Register the sync command.
WP_CLI::add_command('uploads sync', SyncCommand::class, [
    'shortdesc' => 'Sync the uploads folder with the database.',
    'synopsis' => [
        [
            'type' => 'flag',
            'name' => 'verbose',
            'description' => 'Show verbose output.',
            'optional' => true
        ],
        [
            'type' => 'flag',
            'name' => 'skip-db',
            'description' => 'Skip checking DB and sync all upload files.',
            'optional' => true
        ]
    ]
]);

// Register the clean resized command.
WP_CLI::add_command('uploads clean resized', CleanResizedCommand::class, [
    'shortdesc' => 'Clean resized uploads from the uploads folder.',
]);

// Register the clean db command.
WP_CLI::add_command('uploads clean db', CleanDBCommand::class, [
    'shortdesc' => 'Clean resized uploads from the database.',
]);
