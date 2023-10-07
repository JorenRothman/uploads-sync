# Uploads sync
The Uploads sync Plugin is a WordPress CLI plugin designed to effortlessly clean up the uploads folder and synchronise it with the database. With this plugin, you can effortlessly clean and synchronize your WordPress uploads folder with the database, ensuring that your media library remains up-to-date.

## What does it do?
This plugin does two things:
1. It removes all resized copies from the uploads folder.
2. It adds all files to the database that are present in the uploads folder but not referenced in the database.

**Note**
This plugin currently on supports the default WordPress uploads folder structure `year/month/file`. 

## Installation
1. Download the plugin from the [releases page](https://github.com/jorenrothman/uploads-sync/releases).
2. Install the plugin by running `wp plugin install uploads-sync.zip --activate` in your WordPress installation directory.
   1. Alternatively, you can install the plugin by dropping the `uploads-sync` folder into your `wp-content/plugins` directory and activating it via the wp-admin area.
3. Run `wp uploads` to see all available commands.

## Usage
The plugin adds two command to the WordPress CLI: 

`wp uploads sync`. This command can be used to synchronize the uploads folder with the database. The command accepts the following arguments:

| Argument    | Description                                      | Default |
| ----------- | ------------------------------------------------ | ------- |
| `--verbose` | Run script in verbose mode.                      | `false` |
| `--skip-db` | Skip checking database for existing attachments. | `false` |



`wp uploads clean resized`. This command can be used to clean the uploads folder. The command does not accept any arguments.

`wp uploads clean db`. This command is used to remove all attachments from the database. The command does not accept any arguments.


