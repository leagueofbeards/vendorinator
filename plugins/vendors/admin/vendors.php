<?php namespace Habari; ?>
<?php 
$theme = new StacheAdmin();
$theme->show('header');
$rows = Vendors::get( array('nolimit' => 'true') );
?>
	<div id="title_bar">
		<h3>Vendors</h3>
		<div class="add_button_holder"><a href="<?php Site::out_url('admin'); ?>/add_vendor">Add New Vendor &raquo;</a></div>	
	</div>
	<div id="content_holder">
	<div class="innerpad">   
	    <table width="100%" border="0" cellspacing="0" id="data_sort">
	    	<thead>
	    	<tr>
	        	<th width="2">&nbsp;</th>
	        	<th>Company</th>
	        	<th>Contact Person</th>
	        	<th>Contact Email</th>
	        	<th>Plan</th>
	        	<th>Status</th>
	            <th width="2">&nbsp;</th>
	        </tr>
	        </thead>
	        <tbody>
	        <?php foreach( $rows as $row ) { ?>
	        	<tr>	        	
	        		<td><a href="<?php Site::out_url('admin'); ?>/add_vendor?id=<?php echo $row->id; ?>"><img src="<?php $theme->image('edit.png'); ?>" alt="Edit"></a></td>
		        	<td><a href="<?php Site::out_url('admin'); ?>/add_vendor?id=<?php echo $row->id; ?>"><?php echo $row->title; ?></a></td>
		        	<td><?php echo $row->contact_name; ?></td>
		        	<td><?php echo $row->contact_email; ?></td>
		        	<td><?php echo $row->plan_id; ?></td>
		        	<td><?php echo $row->state; ?></td>
		        	<td><a href=""><img src="<?php $theme->image('delete.png'); ?>" alt="Edit"></a></td>
		        </tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	</div> <!-- content_holder -->
<?php $theme->show('footer'); ?>