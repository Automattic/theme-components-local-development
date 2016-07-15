<?php

/*
Plugin Name: Components Local Development
Plugin URI:  https://github.com/Automattic/components-local-development
Description: Enables testing a local copy of Components on a local copy of http://components.underscores.me/.
Version:     1.0
Author:      Automattic
Author URI:  https://automattic.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: theme-components-local-dev
*/

class Theme_Components_Dev_Plugin {
	var $repo_directory = 'theme-components';
	var $repo_file_name = 'theme-components-master.zip';

	function __construct() {
		// We're in local dev mode, so create and add that filter. Used by the Components generator.
		$this->local_dev = apply_filters( 'components_local_dev', true );
		add_filter( 'components_local_dev', '__return_true' );

		// Apply the filter to bypass the generator's cache. This makes the generator rebuild themes on every page load.
		add_filter( 'components_bypass_cache', '__return_true' );

		// Let's run an init function to set things up.
		// Using a low priority to make sure it runs before other actions.
		add_action( 'init', array( $this, 'components_local_dev_init' ), 1 );
	}

	public function components_local_dev_init() {

		// If the local zip file exist from a previous test, delete it.
		if ( file_exists( $this->repo_file_name ) ) {
			$this->delete_file( $this->repo_file_name );
		}

		// Create a new local zip file, which the generator uses instead of downloading a copy from Github.
		$this->zippity_zip_directory( $this->repo_directory );
	}

	/**
	 * This zips local Components repo.
	 */
	public function zippity_zip_directory( $zip_directory ) {
		if ( file_exists( $zip_directory ) && is_dir( $zip_directory ) ) {
			// Get real path for our folder.
			$root_path = realpath( $zip_directory );

			// Initialize archive object.
			$zip = new ZipArchive();
			$zip->open( 'theme-components-master.zip', ZipArchive::CREATE && ZipArchive::OVERWRITE );

			// Files and directories to ignore.
			$exclude_files = array( '.git', '.svn', '.DS_Store', '.', '..' );
			$exclude_directories = array( '.git', '.svn', '.', '..' );

			// Create recursive directory iterator.
			$files = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $root_path ),
				RecursiveIteratorIterator::LEAVES_ONLY
			);

			foreach ( $files as $name => $file ) {
				if ( in_array( basename( $file ), $exclude_files ) ) {
					continue;
				}
				foreach ( $exclude_directories as $directory )
					if ( strstr( $file, "/{$directory}/" ) ) {
						continue 2; // continue the parent foreach loop
					}
				// Skip directories (they would be added automatically).
				if ( ! $file->isDir() ) {
					// Get real and relative path for current file.
					$file_path = $file->getRealPath();
					$relative_path = substr( $file_path, strlen( $root_path ) + 1 );

					// Add current file to archive.
					$zip->addFile( $file_path, 'theme-components-master/' . $relative_path );
				}
			}

			// Zip archive will be created only after closing object.
			$zip->close();
		} else {
			wp_die( __( 'Error: <code>' . $zip_directory . '</code> directory does not exist. Make sure you put a copy of <a href="https://github.com/Automattic/theme-components" target="_blank">Theme Components</a> in the root of your WordPress install so you can develop Components locally.', 'theme-components-local-dev' ) );
		}
	}

	/**
	 * This deletes a file.
	 */
	public function delete_file( $URI ) {
		if ( ! unlink( $URI ) ) {
			 wp_die( __( 'Error: ' . $URI . ' file was not able to be deleted.', 'theme-components-local-dev' ) );
		}
	}
}

if ( ! is_admin() ) {
	new Theme_Components_Dev_Plugin;
}
