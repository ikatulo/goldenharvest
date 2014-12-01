<?php
/* Template Name: Invoice */
$uri = explode("/", $_SERVER['REQUEST_URI']);

if(count($uri) == 5){
	header('Location:'.home_url().'/quotations/');
}
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>
<?php 

	$quotation = get_post($uri[4]);

	$args = array(
		"post_type" => "customer",
		"post_status" => "publish",
		"posts_per_page" => -1
	);

	$customers = new WP_Query($args);

	$customerId = get_field('customer_id', $quotation->ID);
	$price = get_field('total_price', $quotation->ID);
	$gst = get_field('gst', $quotation->ID);
	$pricegst = get_field('total_price_gst', $quotation->ID);
	$currCustomerType = wp_get_post_terms($quotation->ID, 'customertype');
	$currPaymentStatus = wp_get_post_terms($quotation->ID, 'paymentstatus');
	$currQuotationStatus = wp_get_post_terms($quotation->ID, 'quotationstatus');
	$paymentStatus = get_terms('paymentstatus', array('hide_empty'=>false));
	$quotationStatus = get_terms('quotationstatus', array('hide_empty'=>false));
	$currCustomer = get_post($customerId);	

?>



	<div class="quotation_pdf_container">

			<div id="ghe_logo">
				<img src="<?php echo bloginfo('template_directory');?>/images/ghe_logo.jpg">
			</div>

		<hr class="hrstyle1">

			<div class="quotation_top">



					<div class="quotation_top_q">
						<p id="quotation_text">QUOTATION</p>
					</div>
					
						<div class="quotation_top_cont">

							<div class="quotation_top_customer">

 								<div class="quotation_top_inside">
									 <p><span class="quotation_invoice_bold"><?php echo $currCustomer->post_title; ?></span>
									<br><?php echo get_field('address', $currCustomer->ID); ?><br>
									TEL: <?php echo get_field('phone', $currCustomer->ID); ?></p>
									<p>ATTN: <?php echo get_field('full_name', $currCustomer->ID);?></p>	
								</div>

							</div>

							<div class="quotation_top_quotation">
								Quotation No <br>
								Date <br>
								Your Ref <br>
								Our Ref <br>
								Person in Charge <br>
								Term
							</div>

							<div class="quotation_top_quotation1">
								: <?php echo $quotation->post_title;?><br>
								: 10 Nov 2014<br>
								: <br>
								: <br>
								: <br>
								:
							</div>

						</div>

			</div>

			<div class="quotation_description">

				<table class="quotation_table">
					<tr>

						<th width="590px;" style="text-align: center; border-bottom: solid thin black; font-weight: normal;">
						DESCRIPTION
						</th>

						<th width="130px;" colspan="2" style="text-align: center; border-bottom: solid thin black;  font-weight: normal;">
						AMOUNT
						</th>



					</tr>
					<tr style="height: 330px;">
						<td style="text-align: top; vertical-align: top; ">
							"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"
							"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?"
						</td>
						<td style="text-align: left;">
							S$
						</td>
						<td style="text-align: right;">
							<?php echo $price;?>
						</td>
					</tr>

					<tr style="height: 20px;">
						<td style="text-align: right;">
							DISCOUNT
						</td>
						<td style="text-align: left;">
							S$
						</td>
						<td style="text-align: right; border-bottom-right-radius: 25px;">
							0.00
						</td>					
					</tr>

				</table>

			</div>

			<div class="quotation_bottom">
				
				<div class="quotation_terms">
					Terms &amp; Conditions<br>
					1) Validity of this quotation is 2 weeks.<br>
					2) Any works not within quote are subjected to additional<br>
					3) Cheque is to made payable to '<strong>Golden Harvest Engineering Pte Ltd</strong>'.<br>
				</div>

				<div class="quotation_total">
					Sub-Total<br>
					Add 7% GST<br>
					Total Amount<br>
				</div>

				<div class="quotation_total_container">

					<div class="quotation_total_table">
						S$<br>
						S$<br>
						S$<br>
					</div>

					<div class="quotation_total_table_value">
						<?php echo $price;?><br>
						<?php echo $gst;?><br>
						<?php echo $pricegst;?><br>
					</div>

				</div>

				<div class="quotation_bottom_ty">
					We hope that the above is to your acceptance and look forward to receive your reply soon.
					Thank you and best regards.<br>

				</div>

				<div class="quotation_bottom_sign">
					<div class="quotation_bottom_sign_1">
					Yours faithfully,<br>
					For <strong>'Golden Harvest Engineering Pte Ltd'</strong><br>
					<img src="<?php echo bloginfo('template_directory');?>/images/signature.jpg" width="80%">
					</div>

					
					<div class="quotation_bottom_sign_2">
					Confirmed &amp; Accepted By:
					<img src="<?php echo bloginfo('template_directory');?>/images/signature2.jpg" width="80%"><br>
					Customer's Signature &amp; Co. Stamp
					</div>

				</div>

			</div>




	</div>

<a data-toggle="modal" data-target="#sigModal" class="btn btn-primary" href="#draw-it"> Add Signature</a>




		<div class="modal fade" id="sigModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">

			      <div class="modal-header">     
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel">Draw your signature</h4>
			      </div>

				      <div class="modal-body">




				  <form method="POST" action="" class="sigPad">

				   
				    <ul class="sigNav">
				      <li class="clearButton"><a href="#clear">Clear</a></li>
				    </ul>

				    <div class="sig sigWrapper">
				      <div class="typed"></div>
				      <canvas class="pad" width="198" height="55"></canvas>
				      <input type="hidden" name="output" class="output">
				    </div>




				      </div>

			      <div class="modal-footer">
			        <a href="#" class="btn btn-default" data-dismiss="modal" id="cancel">Cancel</a>
			        <button name="submit_sig" type="submit" class="btn btn-primary" >Save changes</button>
			      </div>
			      
 				 </form>


		    </div>
		  </div>
		</div>






<?php


    if (isset($_POST['output'])){

        require_once 'signature/signature-to-image.php';

        $sig = filter_input(INPUT_POST, 'output', FILTER_UNSAFE_RAW);
        $img = sigJsonToImage($sig);

        imagepng($img, 'signature.png');
        imagedestroy($img);

        }
?>



  <script>
    $(document).ready(function() {
      $('.sigPad').signaturePad({drawOnly:true});
    });
  </script>
  


<?php 

// var_dump($quotation);

// echo 'Current Customer Type : '.$currCustomerType; var_dump($currCustomerType);

// var_dump($quotation->post_content);

// var_dump($currCustomerType);

// var_dump($currCustomer); 

// var_dump($price);

// echo 'Status Slug: '.$status->slug;


?>


<?php get_footer();?>

<?php else:?>

    <?php header('Location:'.home_url());?>

<?php endif;?>