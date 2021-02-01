<?php require "template/header.php"?>

<h1>All Tasks</h1>

<div>
<?php foreach ($tasks as $task): ?>
<li class="<?=$task->complete ? "strikethrough" : ""?>">
    <span>
        <?=$task->description?>
    <span>
    |
    <a href="/tasks/<?=$task->id?>">
       View
    </a>
</li>
<?php endforeach;?>
</div>

<form action="/tasks" method="POST">
    <input type="text" name="description">
    <button type="submit">Submit</button>
</form>

<?php require "template/footer.php"?>
