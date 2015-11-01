<div class="actions columns large-2 medium-3">
	<h3><?= __('Actions') ?></h3>
	<ul class="side-nav">
		<li><?= $this->Html->link(__('Edit Logo'), ['action' => 'edit', $logo->id]) ?> </li>
		<li><?= $this->Form->postLink(__('Delete Logo'), ['action' => 'delete', $logo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $logo->id)]) ?> </li>
		<li><?= $this->Html->link(__('List Logos'), ['action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__('New Logo'), ['action' => 'add']) ?> </li>
		
		
	</ul>
</div>
<div class="logos view large-10 medium-9 columns">
	<h2><?= h($logo->id) ?></h2>
	<div class="row">
		<div class="large-5 columns strings">
			<h6 class="subheader"><?= __('Logo') ?></h6>
			<p><?= h($logo->logo) ?></p>
		</div>
		<div class="large-2 columns numbers end">
			<h6 class="subheader"><?= __('Id') ?></h6>
			<p><?= $this->Number->format($logo->id) ?></p>
			<h6 class="subheader"><?= __('Active') ?></h6>
			<p><?= $this->Number->format($logo->active) ?></p>
		</div>
	</div>
</div>
