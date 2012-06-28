<?php
include_once("global/includes.php");
if (!$user->loggedIn($database)) {
  header("Location: index.php");
}

if (isset($_POST['form'])) {
  $createForm = $database->create_or_update_form($user, $_POST['form']);
  header("Location: ".$createForm['location']."?status=".$createForm['status']);
}

start_html($user, $database, "UCMC Radiation Oncology QA", "Manage Forms", $_REQUEST['status']);

switch($_REQUEST['action']) {
  case 'new':
    echo "<h1>Create a form</h1>
";
    display_form_edit_form($database);
    break;
  case 'edit':
    echo "<h1>Modify a form</h1>
";
    display_form_edit_form($database, intval($_REQUEST['id']));
    break;
  default:
  case 'index':
    display_forms($database);
    echo "<a href='form.php?action=new'>Add a new form</a><br />
";
    break;
}
display_footer();
?>