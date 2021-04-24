<?php
	
	if (empty($_POST))
	{
		?>
			<html>
			<body>

			<form action="" method="post">
			E-mail: <input type="email" name="email" placeholder="Enter your email"><br>
			<input type="submit">
			</form>

			</body>
			</html> 
		<?php
	} else {

		$html = '
			<html>
			  <head>
				<title>Stripe report for GitKraken</title>
				<style>
					.divTable{
						display: table;
						width: 100%;
					}
					.divTableRow {
						display: table-row;
					}
					.divTableHeading {
						background-color: #EEE;
						display: table-header-group;
					}
					.divTableCell, .divTableHead {
						border: 1px solid #999999;
						display: table-cell;
						padding: 3px 10px;
					}
					.divTableHeading {
						background-color: #EEE;
						display: table-header-group;
						font-weight: bold;
					}
					.divTableFoot {
						background-color: #EEE;
						display: table-footer-group;
						font-weight: bold;
					}
					.divTableBody {
						display: table-row-group;
					}		
				</style>
			  </head>
			  <body>
				  <h1>Stripe report for GitKraken</h1>
				 <div class="divTable">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="divTableCell">id</div>
							<div class="divTableCell">object</div>
							<div class="divTableCell">account_country</div>
							<div class="divTableCell">account_name</div>
							<div class="divTableCell">account_tax_ids</div>
							<div class="divTableCell">amount_due</div>
							<div class="divTableCell">amount_paid</div>
							<div class="divTableCell">amount_remaining</div>
							<div class="divTableCell">application_fee_amount</div>
							<div class="divTableCell">attempt_count</div>
							<div class="divTableCell">attempted</div>
							<div class="divTableCell">auto_advance</div>
							<div class="divTableCell">billing_reason</div>
							<div class="divTableCell">charge</div>
							<div class="divTableCell">collection_method</div>
							<div class="divTableCell">created</div>
							<div class="divTableCell">currency</div>
							<div class="divTableCell">custom_fields</div>
							<div class="divTableCell">customer</div>
							<div class="divTableCell">customer_address</div>
							<div class="divTableCell">customer_email</div>
							<div class="divTableCell">customer_name</div>
							<div class="divTableCell">customer_phone</div>
							<div class="divTableCell">customer_shipping</div>
							<div class="divTableCell">customer_tax_exempt</div>
						</div>';

		require_once('vendor/autoload.php');

		$stripe = new \Stripe\StripeClient(  'sk_test_51IjiJJBSSHiYRhIxgy0tVtrfRITbkghwTOj6CJP90yMES2AnDalqSJV3h3qFywBcX7E4kkZHp4RddM316gIlGc4X00nMGwbYsS' );

		$invoices = $stripe->invoices->all(['limit' => 3])->data;

		foreach ($invoices as $invoice) {

			$html.= '<div class="divTableRow">';
				$html.= '<div class="divTableCell">' . $invoice['id'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['object'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['account_country'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['account_name'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['account_tax_ids'] . '</div>';
				$html.= '<div class="divTableCell">' . number_format($invoice['amount_due']/100,2) . '</div>';
				$html.= '<div class="divTableCell">' . number_format($invoice['amount_paid']/100,2) . '</div>';
				$html.= '<div class="divTableCell">' . number_format($invoice['amount_remaining']/100,2) . '</div>';
				$html.= '<div class="divTableCell">' . number_format($invoice['application_fee_amount']/100,2) . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['attempt_count'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['attempted'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['auto_advance'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['billing_reason'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['charge'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['collection_method'] . '</div>';
				$html.= '<div class="divTableCell">' . date("Y-m-d H:i:s",$invoice['created'])  . '</div>';
				$html.= '<div class="divTableCell">' . strtoupper($invoice['currency'])  . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['custom_fields'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['customer'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['customer_address'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['customer_email'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['customer_name'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['customer_phone'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['customer_shipping'] . '</div>';
				$html.= '<div class="divTableCell">' . $invoice['customer_tax_exempt'] . '</div>';
			$html.= '</div>';

			//echo "<pre>"; print_r($invoice); echo "</pre>---";
		}

		$html.= "</div></div></body></html>";


        echo $html;

        function run($message)
        {
            try {
                $mailchimp = new MailchimpTransactional\ApiClient();
                $mailchimp->setApiKey('UvJi7AMv5Kj6wE4CIN3y0g');

                $response = $mailchimp->messages->send(["message" => $message]);
                print_r($response);
            } catch (Error $e) {
                echo 'Error: ', $e->getMessage(), "\n";
            }
        }

        $message = [
            "from_email" => "test@gk.davidmorles.com",
            "subject" => "Stripe report for BitKraken",
            "html" => $html,
            "to" => [
                [
                    "email" => "$_POST[email]",
                    "type" => "to"
                ]
            ]
        ];
        run($message);
	}
?>