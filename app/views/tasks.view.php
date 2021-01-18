<?php require "template/header.php"?>

<h1>All Tasks</h1>

<div>
<?php foreach ($tasks as $task): ?>
<li class="<?=$task->complete ? "strikethrough" : ""?>">
    <a href="/tasks/<?=$task->id?>">
        <?=$task->description?>
    </a>
</li>
<?php endforeach;?>
</div>

<form action="/tasks" method="POST">
    <input type="text" name="description">
    <button type="submit">Submit</button>
</form>

<?php require "template/footer.php"?>
