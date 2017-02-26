<?php if (!empty($messages)): ?>
	<?php //debug ($messages);die;?>
	<?php foreach ($messages as $message):?>
		<li id="<?php echo $message['Message']['id']; ?>" class="messages-item">
			<a href="#">
				<span class="messages-item-star" title="Mark as starred"><i class="fa fa-star"></i></span>
				<?php 
				if (!empty($message['Recipient']['image'])){
					echo $this->Html->image('../uploads/users/'.$message['Recipient']['image'], array(
						'class' =>'messages-item-avatar bordered border-primary',
						'alt' =>  $message['Recipient']['image'],
					));
				}else{
					echo $this->Html->image('../uploads/users/default-user.png', array(
						'class' =>'messages-item-avatar bordered border-primary',
						'alt' =>  'User', 
					));
				}
				?>
				<span class="messages-item-from" from = "<?php echo $message['Sender']['email'];?>">
					de <?php echo $message['Sender']['first_name'] .' '.$message['Sender']['last_name'];?><br> 
					à <?php echo $message['Recipient']['first_name'] .' '.$message['Recipient']['last_name'];?>
				</span>
				<div class="messages-item-time">
					<span class="text"><?php echo date('d-m-Y H:i:s', strtotime($message['Message']['created']));?></span>
				</div>
				<span class="messages-item-subject"><?php echo $message['Message']['title'];?></span>
				<span class="messages-item-content">
					<div class="msg hidden" ><?php echo $message['Message']['body'];?></div>
				</span>
			</a>
		</li>
	<?php endforeach;?>
	<div class="ajax-paginate">
	<?php
		if( $this->Paginator->counter(array('format' => ('{:pages}'))) > $this->Paginator->counter(array('format' => ('{:page}')))){
			echo $this->Html->link('Afficher plus de messages', '#', array(
				'class' => 'btn btn-wide btn-block',
				'id' => $this->Paginator->counter(array('format' => ('{:page}')))+1
			));
		};
	?>
	</div>
<?php endif; ?>