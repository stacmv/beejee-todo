<article class="app_content">
    <div class="tasks">
        <h2>
            Tasks (<?=$pager->items_count;?>)
            <div class="pull-right">
                <a class="btn" href="task/form/add">+ New</a>
            </div>
        </h2>

        <?php if (empty($tasks)):?>
            <div class="alert alert-info">
                Add your first task. Press <a class="btn btn-small" href="task/form/add">+ New</a> button.
            </div>
        <?php else: ?>
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>
                        <a href="?page=1&sort=<?=($sort == 'name' ? '-name' : 'name');?>">
                            Name
                            <?php if ($sort == 'name'):?><i class="icon-arrow-down"></i><?php endif;?>
                            <?php if ($sort == '-name'):?><i class="icon-arrow-up"></i><?php endif;?>
                        </a>
                    </th>
                    <th>
                        <a href="?page=1&sort=<?=($sort == 'email' ? '-email' : 'email');?>">
                            E-mail
                            <?php if ($sort == 'email'):?><i class="icon-arrow-down"></i><?php endif;?>
                            <?php if ($sort == '-email'):?><i class="icon-arrow-up"></i><?php endif;?>
                        </a>
                    </th>
                    <th>
                        <a href="?page=1&sort=<?=($sort == 'text' ? '-text' : 'text');?>">
                            Task
                            <?php if ($sort == 'text'):?><i class="icon-arrow-down"></i><?php endif;?>
                            <?php if ($sort == '-text'):?><i class="icon-arrow-up"></i><?php endif;?>
                        </a>
                    </th>
                    <?php if ($USER):?>
                        <th>...</th>
                    <?php endif;?>
                </tr>
                <?php foreach ($tasks as $task):?>
                    <tr>
                        <td>
                            <?php if ($USER):?>
                                <?php if ($task->state == \Models\Task::COMPLETED):?>
                                    <a data-method="post" href="task/uncomplete?id=<?=$task->id;?>">✅</a>
                                <?php else :?>
                                    <a data-method="post" href="task/complete?id=<?=$task->id;?>">⬜</a>
                                <?php endif;?>
                            <?php else:?>
                                <?php if ($task->state == \Models\Task::COMPLETED):?>
                                    ✅
                                <?php else :?>
                                    ⬜
                                <?php endif;?>
                            <?php endif;?>
                        </td>
                        <td><?=$task->name;?></td>
                        <td><?=$task->email;?></td>
                        <td>
                            <?=$task->text;?>
                            <?php if ($task->edited == \Models\Task::EDITED) :?>
                                <i title="Task was edited by admin" class="icon-pencil"></i>
                            <?php endif;?>
                        </td>
                        <?php if ($USER) :?>
                            <td><a class="btn btn-small" href="task/form/edit?id=<?=$task->id;?>">Edit</a></td>
                        <?php endif;?>
                    </tr>
                <?php endforeach;?>
            </table>
        <?php endif;?>

        <?php if (!empty($pager) && ($pager->pages_count > 1)):?>
            <div class="pagination pagination-centered">
                <ul>
                    <?php if($pager->current > 1):?>
                        <li><a href="<?=$pager->links()[$pager->current - 1];?>">«</a></li>
                    <?php else:?>
                        <li class="disabled"><a href="#">«</a></li>
                    <?php endif;?>
                    <?php foreach($pager->iterator as $v):?>
                        <?php if (is_numeric($v)):?>
                            <li <?php if($pager->current == $v):?>class="active"<?php endif;?>><a href="<?=$pager->links()[$v];?>"><?=$v;?></a></li>
                        <?php else:?>
                            <li class="disabled"><a href="#"><?=$v;?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>
                    <?php if($pager->current < $pager->pages_count):?>
                        <li><a href="<?=$pager->links()[$pager->current + 1];?>">»</a></li>
                    <?php else:?>
                        <li class="disabled"><a href="#">»</a></li>
                    <?php endif;?>
                </ul>
            </div>
        <?php endif;?>

    </div>
</article>
