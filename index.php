<?php
	
	if (empty($_POST)) 
	{
		require('form.html');
	} else {

		// Initialization
		
		$ini = parse_ini_file('gk.ini');
		require_once('vendor/autoload.php');
		require('report.php');

		// Obtain report from Stripe data

		$stripe = new \Stripe\StripeClient( $ini['stripe_api_key'] );
		$html = report($stripe->invoices->all( ['limit' => 100] )->data); 

		// Echo report
		
        	echo $html; 
		
		// Prepare email

		$message = [
		    'from_email' => $ini['from_email'],
		    'subject' => $ini['email_subject'],
		    'html' => $html,
		    'to' => [
			[
			    'email' => $_POST['email'],
			    'type' => 'to'
			]
		    ]
		];
		
		// Send email
	
		try {
			$mailchimp = new MailchimpTransactional\ApiClient();
			$mailchimp->setApiKey( $ini['mailchimp_api_key'] );

			$response = $mailchimp->messages->send( ['message' => $message] );
			print_r($response);
		} catch (Error $e) {
			echo 'Error: ', $e->getMessage(), '\n';
		}
  }
?>
