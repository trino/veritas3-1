<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('List Logos'), ['action' => 'index']) ?></li>
	</ul>
</div>
<div class="logos form large-10 medium-9 columns">
	<?= $this->Form->create($logo); ?>
	<fieldset>
		<legend><?= __('Add Logo') ?></legend>
		<?php
			echo $this->Form->input('logo');
			echo $this->Form->input('active');
		?>
	</fieldset>
	<?= $this->Form->button(__('Submit')) ?>
	<?= $this->Form->end() ?>
</div>
