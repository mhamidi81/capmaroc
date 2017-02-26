<?php
$crumbs = $this->Html->getCrumbs(
				$this->Html->tag('span', '/', array(
						'class' => 'divider',
				))
);
?>
<?php if ($crumbs): ?>
	<div class="breadcrumb" id="breadcrumb-container">
		<?php echo $crumbs; ?>
	</div>
	<?php
 endif;
