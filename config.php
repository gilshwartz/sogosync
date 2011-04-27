<?php
/***********************************************
* File      :   config.php
* Project   :   Z-Push
* Descr     :   Main configuration file
*
* Created   :   01.10.2007
*
* Copyright 2007 - 2010 Zarafa Deutschland GmbH
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License, version 3,
* as published by the Free Software Foundation with the following additional
* term according to sec. 7:
*
* According to sec. 7 of the GNU Affero General Public License, version 3,
* the terms of the AGPL are supplemented with the following terms:
*
* "Zarafa" is a registered trademark of Zarafa B.V.
* "Z-Push" is a registered trademark of Zarafa Deutschland GmbH
* The licensing of the Program under the AGPL does not imply a trademark license.
* Therefore any rights, title and interest in our trademarks remain entirely with us.
*
* However, if you propagate an unmodified version of the Program you are
* allowed to use the term "Z-Push" to indicate that you distribute the Program.
* Furthermore you may use our trademarks where it is necessary to indicate
* the intended purpose of a product or service provided you use it in accordance
* with honest practices in industrial or commercial matters.
* If you want to propagate modified versions of the Program under the name "Z-Push",
* you may only do so if you have a written permission by Zarafa Deutschland GmbH
* (to acquire a permission please contact Zarafa at trademark@zarafa.com).
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* Consult LICENSE file for details
************************************************/

/**********************************************************************************
 *  Default settings
 */
    // Defines the default time zone
    date_default_timezone_set("Europe/Amsterdam");

    // Defines the base path on the server
    define('BASE_PATH', dirname($_SERVER['SCRIPT_FILENAME']). '/');

    // Define the include paths
    ini_set('include_path',
                        BASE_PATH. PATH_SEPARATOR .
                        BASE_PATH. 'include/'. PATH_SEPARATOR .
                        BASE_PATH. 'libs/'. PATH_SEPARATOR .
                        BASE_PATH. 'backends/'. PATH_SEPARATOR .
                        ini_get('include_path'). PATH_SEPARATOR .
                        '/usr/share/php/'. PATH_SEPARATOR .
                        '/usr/share/php5/');

    // Try to set unlimited timeout
    define('SCRIPT_TIMEOUT', 0);

    //Max size of attachments to display inline. Default is 1MB
    define('MAX_EMBEDDED_SIZE', 1048576);


/**********************************************************************************
 *  Default FileStateMachine settings
 */
    define('STATE_DIR', '/var/lib/z-push/');


/**********************************************************************************
 *  Logging settings
 */
    define('LOGFILEDIR', '/var/log/z-push/');
    define('LOGFILE', LOGFILEDIR . 'z-push.log');
    define('LOGERRORFILE', LOGFILEDIR . 'z-push-error.log');
    define('LOGLEVEL', LOGLEVEL_INFO);

    // To save e.g. WBXML data only for selected users, add the usernames to the array
    // The data will be saved into a dedicated file per user in the LOGFILEDIR
    define('LOGUSERLEVEL', LOGLEVEL_WBXML);
    $specialLogUsers = array();


/**********************************************************************************
 *  Mobile settings
 */
    // Device Provisioning
    define('PROVISIONING', true);

    // This option allows the 'loose enforcement' of the provisioning policies for older
    // devices which don't support provisioning (like WM 5 and HTC Android Mail) - dw2412 contribution
    // false (default) - Enforce provisioning for all devices
    // true - allow older devices, but enforce policies on devices which support it
    define('LOOSE_PROVISIONING', false);

    // Default conflict preference
    // Some devices allow to set if the server or PIM (mobile)
    // should win in case of a synchronization conflict
    //   SYNC_CONFLICT_OVERWRITE_SERVER - Server is overwritten, PIM wins
    //   SYNC_CONFLICT_OVERWRITE_PIM    - PIM is overwritten, Server wins (default)
    define('SYNC_CONFLICT_DEFAULT', SYNC_CONFLICT_OVERWRITE_PIM);

/**********************************************************************************
 *  Backend settings
 */
    // The data providers that we are using (see configuration below)
    define('BACKEND_PROVIDER', "BackendZarafa");


    // ************************
    //  BackendZarafa settings
    // ************************

    // Defines the server to which we want to connect
    define('MAPI_SERVER', 'file:///var/run/zarafa');


    // ************************
    //  BackendIMAP settings
    // ************************

    // Defines the server to which we want to connect
    // recommended to use local servers only
    define('IMAP_SERVER', 'localhost');
    // connecting to default port (143)
    define('IMAP_PORT', 143);
    // best cross-platform compatibility (see http://php.net/imap_open for options)
    define('IMAP_OPTIONS', '/notls/norsh');
    // overwrite the "from" header if it isn't set when sending emails
    // options: 'username'    - the username will be set (usefull if your login is equal to your emailaddress)
    //        'domain'    - the value of the "domain" field is used
    //        '@mydomain.com' - the username is used and the given string will be appended
    define('IMAP_DEFAULTFROM', '');
    // copy outgoing mail to this folder. If not set z-push will try the default folders
    define('IMAP_SENTFOLDER', '');
    // forward messages inline (default off - as attachment)
    define('IMAP_INLINE_FORWARD', false);
    // use imap_mail() to send emails (default) - off uses mail()
    define('IMAP_USE_IMAPMAIL', true);


    // ************************
    //  BackendMaildir settings
    // ************************
    define('MAILDIR_BASE', '/tmp');
    define('MAILDIR_SUBDIR', 'Maildir');

    // **********************
    //  BackendVCDir settings
    // **********************
    define('VCARDDIR_DIR', '/home/%u/.kde/share/apps/kabc/stdvcf');

    // Alternative backend to perform SEARCH requests (GAL search)
    // if an empty value is used, the default search functionality of the main backend is used
    // use 'SearchLDAP' to search in a LDAP directory (see backend/searchldap/config.php)
    define('SEARCH_PROVIDER', '');


/**********************************************************************************
 *  Synchronize additional folders to all mobiles
 *
 *  With this feature, special folders can be synchronized to all mobiles.
 *  This is useful for e.g. global company contacts.
 *
 *  This feature is supported only by certain devices, like iPhones.
 *  Check the compatibility list for supported devices:
 *      http://z-push.sf.net/compatibility
 *
 *  To synchronize a folder, add a section setting all parameters as below:
 *      store:      the ressource where the folder is located.
 *                  Zarafa users use 'SYSTEM' for the 'Public Folder'
 *      folderid:   folder id of the folder to be synchronized
 *      name:       name displayed on the mobile device
 *      type:       supported types are:
 *                      SYNC_FOLDER_TYPE_USER_CONTACT
 *                      SYNC_FOLDER_TYPE_USER_APPOINTMENT
 *                      SYNC_FOLDER_TYPE_USER_TASK
 *                      SYNC_FOLDER_TYPE_USER_MAIL
 *
 *  Additional notes:
 *  - all Z-Push users MUST HAVE FULL write permissions to the configured folders!
 *  - this feature is only partly suitable for multi-tenancy environments,
 *    as ALL users from all tenents need access to the configured store & folder.
 *  - use the zarafa_listfolders.php script to get a list of available folders
 *  - changing this configuration could cause high load on the system, as all
 *    connected devices will be updated and load the data contained in the
 *    added/modified folders.
 */

    $additionalFolders = array(
        // demo entry for the synchronization of contacts from the public folder.
        // uncomment (remove '/*' '*/') and fill in the folderid
/*
        array(
            'store'     => "SYSTEM",
            'folderid'  => "",
            'name'      => "Public Contacts",
            'type'      => SYNC_FOLDER_TYPE_USER_CONTACT,
        ),
*/
    );

?>