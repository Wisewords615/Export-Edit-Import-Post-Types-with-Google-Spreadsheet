=== Mass Export/Edit/Import Post Types with Google spreadsheet ===<br>
Contributors: @wisewords<br>
Donate link: https://donorbox.org/eeip<br>
Tags: Google Spreadsheet,import,export,edit,post types<br>
Requires at least: 4.4.0<br>
Tested up to: 4.6<br>
Stable tag: 4.6<br>
License: GPLv2 or later<br>
License URI: http://www.gnu.org/licenses/gpl-2.0.html<br>

Mass edit/import/export plugin based on google spreadsheet.<br>

<img src="https://s30.postimg.org/mqefdja01/Screen_Shot_2016_12_12_at_23_13_31.png" />

== Description ==

EEIP - Mass Export/Edit/Import Post Types with Google spreadsheet

Wordpress plugin for mass data managing.
With this plugin you can export and edit post data in google spreadsheet.
Plugin works with Custom post types and Wordpress Core Post types.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `/Mass-Export-Edit-Import-Post-Types-with-Google spreadsheet/` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Connect plugin with your google account under the ‘Settings’ menu is ‘EEIP’ menu item.

== Connecting Google Account == 

1. Go to https://console.developers.google.com/apis/ and login
2. Create project if needed
3. Activate Google Drive API by Searching for ‘Google Drive API’ 
4. Activate Google Sheets API by Searching for ‘Google Sheets API’
5. Afther activating api’s go to : https://console.developers.google.com/apis/credentials and click ‘Create credentials’, next select ‘OAuth client ID’.
6. Creating ‘OAuth client ID’ Step By Step
6.1. Application type -> Web application
6.2. Enter Name -> Name
6.3. Authorized JavaScript origins -> http://domain.com
6.4. Authorized redirect URIs -> http://domain.com/wp-admin/options-general.php?page=eeip
6.5. Submit form and save Web application Name,Client ID and Client secret for later use.
7. Go to link :http://domain.com/wp-admin/options-general.php?page=eeip
		 and Enter Web application Name,Your Google Drive Email,Client ID and Client secret 

				Click save and plugin is ready for use.

== Frequently Asked Questions ==

= Can i edit Custom Post Types with EEIP? =

	Yes you can.


== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.



