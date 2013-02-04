<?php namespace Habari; ?>
<?php $theme = new StacheAdmin(); ?>
<?php $theme->show( 'header' ); ?>
<?php
	if (isset($_GET['id'])) {
		$vendor = Vendor::get( array('id' => $_GET['id']) );
	} else {
		$vendor = '';
	}
?>
	<div id="title_bar">
		<?php if( is_object($vendor) ) { ?>
		<h3><?php echo $vendor->title; ?></h3>
		<?php } else { ?>
		<h3>Add Vendor</h3>
		<?php } ?>
		<div class="add_button_holder"><a href="<?php Site::out_url('admin'); ?>/vendors"> &laquo; Back to Vendors</a></div>	
	</div>
	
	<div class="messagepass"></div>
	<div class="messagefail"></div>
	
	<div id="content_holder">
	<div id="inner_left_column">
	
	<?php if( is_object($vendor) ) { ?>
		<form id="membership_item" action="<?php Site::out_url('habari'); ?>/auth_ajax/update_vendor" method="post" enctype="multipart/form-data">
			<input type="hidden" value="<?php echo $vendor->id; ?>" name="vendor_id" id="membership_id">
	<?php } else { ?>
		<form id="membership_item" action="<?php Site::out_url('habari'); ?>/auth_ajax/add_vendor" method="post" enctype="multipart/form-data">
	<?php } ?>
		
	<div class="grey_contain">
		<ul>
			<li>
			<?php if ( is_object($vendor) && $vendor->state == 1 ) { $active_checked = "checked"; } else { $active_checked = ''; } ?>
				<input type="checkbox" name="active" value="yes" <?php echo $active_checked ?> />&nbsp;&nbsp;Active?&nbsp;&nbsp;
			</li>
       	</ul>    
	</div>
	
	<div class="form100">
		<label for="title">Name:</label>
		<input type="text" name="title" id="title" value="<?php echo $vendor ? $vendor->title : ''; ?>" class="field100" tabindex="1" />
	</div>
	
	<div class="form50">
		<label for="time">Contact Name:</label>
		<input type="text" name="contact_name" id="contact_name" value="<?php echo $vendor ? $vendor->contact_name : ''; ?>" class="field50" tabindex="2" />
	</div>
	
	<div class="form50">
		<label for="base_price">Contact Email:</label>
		<input type="text" name="contact_email" id="contact_email" value="<?php echo $vendor ? $vendor->contact_email : ''; ?>" class="field50" tabindex="3" />
	</div>

	<div class="form50">
		<label for="base_price">Plan:</label>
		<input type="text" name="plan" id="plan" value="<?php echo $vendor ? $vendor->plan_id : ''; ?>" class="field50" tabindex="3" />
	</div>
	
	<div class="form_button_row">
	<?php if( is_object($vendor) ) { ?>
		<input type="submit" name="submit" value="Update Vendor" class="form_button" />
	<?php } else { ?>
		<input type="submit" name="submit" value="Add Vendor" class="form_button" />
	<?php } ?>
	</div>
	
	</div> <!-- inner_left_column -->
	</form>
	</div>
	</div>	
<?php $theme->show( 'footer' ); ?>