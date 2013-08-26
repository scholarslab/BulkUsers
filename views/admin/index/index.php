<?php
$head = array('title' => html_escape(__('Bulk Users')));
echo head($head);
?>
<?php echo flash(); ?>

<?php echo $this->form; ?>

<?php echo foot(); ?>
