<div class="games index large-9 medium-8 columns content">
    <h3><?= __('Games') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('start_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('end_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('total_plays') ?></th>
                <th scope="col"><?= $this->Paginator->sort('total_checked') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ratio') ?></th>
                <th scope="col"><?= $this->Paginator->sort('complete') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($games as $game): ?>
            <tr>
                <td><?= $this->Number->format($game->id) ?></td>
                <td><?= h($game->start_time) ?></td>
                <td><?= h($game->end_time) ?></td>
                <td><?= $this->Number->format($game->total_plays) ?></td>
                <td><?= $this->Number->format($game->total_checked) ?></td>
                <td><?= $this->Number->format($game->ratio) ?></td>
                <td><?= h($game->complete) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $game->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $game->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $game->id], ['confirm' => __('Are you sure you want to delete # {0}?', $game->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
