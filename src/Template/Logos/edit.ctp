<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Form->postLink(
				__('Delete'),
				['action' => 'delete', $logo->id],
				['confirm' => __('Are you sure you want to delete # {0}?', $logo->id)]
			)
		?></li>
		<li><?= $this->Html->link(__('List Logos'), ['action' => 'index']) ?></li>
	</ul>
</div>
<div class="logos form large-10 medium-9 columns">
	<?= $this->Form->create($logo); ?>
	<fieldset>
		<legend><?= __('Edit Logo') ?></legend>
		<?php
			echo $this->Form->input('logo');
			echo $this->Form->input('active');
		?>
	</fieldset>
	<?= $this->Form->button(__('Submit')) ?>
	<?= $this->Form->end() ?>
</div>
