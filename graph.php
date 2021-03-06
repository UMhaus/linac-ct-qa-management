<?php
include_once("global/includes.php");
if (!$user->loggedIn()) {
  redirect_to(array('location' => 'index.php', 'status' => 'Please log in to plot history.'));
}

switch($_REQUEST['action']) {
  case 'json':
    display_history_json($user, explode(",", $_REQUEST['form_fields']), explode(",", $_REQUEST['machines']));
    exit;
    break;
  
  default:
  case 'show':
    start_html($user, "UC Medicine QA", "Plot History", $_REQUEST['status'], $_REQUEST['class']);
    echo "<h1>Plot History</h1>
";
    display_history_plot($user, $_REQUEST['form_id']);
    break;

  default:
}
display_footer();
?>