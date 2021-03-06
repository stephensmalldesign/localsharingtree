<form id="gb_merchant_register" method="post" action="<?php gb_merchant_register_url(); ?>" enctype="multipart/form-data">
	<input type="hidden" name="gb_merchant_action" value="<?php echo Group_Buying_Merchants_Registration::FORM_ACTION; ?>" />
	<table class="collapsable form-table">
		<tbody>
			<?php foreach ( $fields as $key => $data ): ?>
				<tr>
					<?php if ( $data['type'] != 'checkbox' ): ?>
						<td><?php gb_form_label($key, $data, 'contact'); ?></td>
						<td><?php gb_form_field($key, $data, 'contact'); ?></td>
					<?php else: ?>
						<td colspan="2">
							<label for="gb_contact_<?php echo $key; ?>"><?php gb_form_field($key, $data, 'contact'); ?> <?php echo $data['label']; ?></label>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php self::load_view('merchant/register-controls', array()); ?>
</form>

<div>
<br />
<p class="merchant_register_note description" style="font-size: smaller">Please add <a href="mailto:noreply@localsharingtree.com">noreply@localsharingtree.com</a> to your address book in order to get notifications about your Merchant Account and Deals.</p>
</div>