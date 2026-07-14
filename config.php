<?php
/**
 * ===========================================================
 *  M-Restaurant — Site Configuration
 * ===========================================================
 *  Edit the values below to control account limits and the
 *  SMS gateway used to notify customers when a payment is
 *  approved.
 * ===========================================================
 */

/* -----------------------------------------------------------
 *  ACCOUNT LIMIT
 *  Maximum number of accounts that can ever register on the
 *  site. Once this number is reached, the registration page
 *  will show a "registration closed" message instead of the
 *  sign-up form. Set to 0 for unlimited.
 * --------------------------------------------------------- */
define('MAX_USER_ACCOUNTS', 100);

/* -----------------------------------------------------------
 *  SMS GATEWAY
 *  This project does not ship with a real SMS account. Every
 *  message is always written to the `sms_logs` table so you
 *  can see exactly what would have been sent.
 *
 *  Once you have a provider (e.g. Twilio, Africa's Talking,
 *  AfroMessage, GeezSMS, etc.) set SMS_PROVIDER_ENABLED to
 *  true and fill in the URL/key/sender below. The helper in
 *  includes/sms_helper.php sends a simple POST request with
 *  api_key / sender_id / to / message fields — adjust the
 *  field names in that file to match your provider's docs if
 *  they differ.
 * --------------------------------------------------------- */
define('SMS_PROVIDER_ENABLED', false);
define('SMS_API_URL', 'https://api.your-sms-provider.com/send');
define('SMS_API_KEY', 'PUT_YOUR_API_KEY_HERE');
define('SMS_SENDER_ID', 'M-Restaurant');

/* -----------------------------------------------------------
 *  Default country for the phone selector on Register.php
 * --------------------------------------------------------- */
define('DEFAULT_COUNTRY_ISO', 'ET'); // Ethiopia
