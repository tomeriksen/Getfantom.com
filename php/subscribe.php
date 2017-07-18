<?php

/*
 * ------------------------------------
 * Mailchimp Email Configuration
 * ------------------------------------
 */

$apiKey       = 'fa4d80bffab98b2533e85c30e5238c8e-us16'; /*Your Mailchiimp API Key*/
$listId       = 'd21809d7f5'; /*Mailchimp List ID  'cf987c5de6'*/
$double_optin = false; /*Set False if you don't need to verify user enmail */
$send_welcome = true; /* Send Welcome email to new users */
$email        = $_POST['email'];
$fname        = '';
$lname        = '';
$club         = $_POST['club'];
$datacenter	  = explode( '-', $apiKey );
$post_url     = 'https://' . $datacenter[1] . '.api.mailchimp.com/2.0/lists/subscribe.json?';

/*
Need to capture First name and last name? use this.
$fname        = $_POST['fname'];
$lname        = $_POST['lname'];
*/


/*
 * ------------------------------------
 * END CONFIGURATION
 * ------------------------------------
 */

/*
 * -------------------------------------------------
 * NERD STUFF BELOW, ONLY EDIT IF YOU ARE A PRO
 * -------------------------------------------------
 */

// Let's put together our user data to send
$post_query_array = array(
    "apikey" => $apiKey,
    "id" => $listId,
    "email" => array(
        "email" => $email,
        "euid" => "",
        "leid" => ""
    ),
    "double_optin" => $double_optin,
    "send_welcome" => $send_welcome,
    "merge_vars" => array( // Build an Array of the different Merge Vars you setup in your account
        'FNAME' => $fname,
        'LNAME' => $lname,
        'CLUB' => $club
    )
);

// We still need to build our HTML query string to send
$post_query_string = http_build_query($post_query_array);

// Make sure we have a User's Email address as this is the only required item
if (!empty($email)) {
    // Submit the data via Curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $post_url); // Post URL
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_query_string); // Our Query string that we built
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $curl_out = curl_exec($ch);
	$data = json_decode($curl_out);
	if ($data->error){
		echo $data->error;
	} else {
		echo 'success';
	}

}
?>
