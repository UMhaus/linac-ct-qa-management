<?php
include_once("global/includes.php");
if (!$user->loggedIn()) {
  header("Location: index.php");
}

if (isset($_POST['facility'])) {
  $createFacility = $database->create_or_update_facility($user, $_POST['facility']);
  redirect_to($createFacility);
}

start_html($user, "UC Medicine QA", "Manage Facilities", $_REQUEST['status'], $_REQUEST['class']);

switch($_REQUEST['action']) {
  case 'new':
    //ensure that user has sufficient privileges to add a facility.
    if (!$user->isAdmin()) {
      display_error("Error: Insufficient privileges", "You must be an administrator to add facilities.");
      break;
    }
    echo "<h1>Add a facility</h1>\n";
    display_facility_edit_form($user);
    break;
  case 'edit':
    if (!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
      display_error("Error: Invalid facility ID", "Please check the facility ID and try again.");
      break;
    }
    if (intval($_REQUEST['id']) != $user->facility['id'] || !$user->isAdmin()) {
      display_error("Error: Insufficient privileges", "You are not allowed to modify this facility.");
      break;
    }
    echo "<h1>Modify a facility</h1>\n";
    display_facility_edit_form($user, intval($_REQUEST['id']));
    break;
  case 'show':
    if (!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
      display_error("Error: Invalid facility ID", "Please check the facility ID and try again.");
      break;
    }
    $facilityName = $database->queryFirstValue("SELECT `name` FROM `facilities` WHERE `id` = ".intval($_REQUEST['id'])." LIMIT 1");
    if (!$facilityName) {
      display_error("Error: Invalid facility ID", "Please check the facility ID and try again.");
      break;
    }
    echo "<h1>".escape_output($facilityName)."</h1>\n";
    display_facility_profile($user, intval($_REQUEST['id']));
    break;
  default:
  case 'index':
    echo "<h1>Facilities</h1>\n";
    display_facilities($user);
    echo "<a href='facility.php?action=new'>Add a new facility</a><br />\n";
    break;
}
display_footer();
?>