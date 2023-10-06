# Upload Sync
The Upload Sync Plugin is a WordPress CLI plugin designed to effortlessly synchronise the uploads folder with the database . With this plugin, you can effortlessly synchronize your WordPress uploads folder with the database, ensuring that your media library remains up-to-date.

## What does it do?
The plugin will scan the uploads folder for files, add them to the database and generate attachment metadata.

**Note**
This plugin currently on supports the default WordPress uploads folder structure `year/month/file`. 

## Installation
1. Download the plugin from the [releases page](https://github.com/jorenrothman/upload-sync/releases).
2. Install the plugin by running `wp plugin install upload-sync.zip --activate` in your WordPress installation directory.
   1. Alternatively, you can install the plugin by dropping the `upload-sync` folder into your `wp-content/plugins` directory and activating it via the wp-admin area.
3. Run `wp upload-sync` to synchronize your uploads folder with the database.

## Usage
The plugin adds a single command to the WordPress CLI: `wp upload-sync`. This command can be used to synchronize the uploads folder with the database. The command accepts the following arguments:

| Argument | Description                 | Default |
| -------- | --------------------------- | ------- |
| `-v`     | Run script in verbose mode. | `false` |


