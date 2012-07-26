﻿<?php

function humanize($str) {
  $str = trim(strtolower($str));
  $str = preg_replace('/_/', ' ', $str);
  $str = preg_replace('/[^a-z0-9\s+]/', '', $str);
  $str = preg_replace('/\s+/', ' ', $str);
  $str = explode(' ', $str);

  $str = array_map('ucwords', $str);

  return implode(' ', $str);
}

function escape_output($input) {
  if ($input == '' || $input == 'NULL') {
    return '';
  }
  return htmlspecialchars($input, ENT_QUOTES, "UTF-8");
}

function redirect_to($location, $status) {
  if (strpos($location, "?") === FALSE) {
    header("Location: ".$location."?status=".$status);
  } else {
    header("Location: ".$location."&status=".$status);
  }
}

function start_html($user, $database, $title="UCMC Radiation Oncology QA", $subtitle="", $status="") {
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>'.escape_output($title).($subtitle != "" ? " - ".$subtitle : "").'</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap-responsive.min.css" type="text/css" />
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" type="text/css" />
	<link rel="stylesheet" href="css/jquery.dataTables.css" type="text/css" />
	<link rel="stylesheet" href="css/linac-qa.css" type="text/css" />
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" language="javascript" src="js/jquery.dropdownPlain.js"></script>
	<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="js/d3.v2.min.js"></script>
  <script type="text/javascript" src="js/d3-helpers.js"></script>
  <script type="text/javascript" src="js/highcharts.js"></script>
  <script type="text/javascript" src="js/exporting.js"></script>
	<script type="text/javascript" language="javascript" src="js/calcFunctions.js"></script>
	<script type="text/javascript" language="javascript" src="js/renderHighCharts.js"></script>
	<script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" language="javascript" src="js/bootstrap-dropdown.js"></script>
	<script type="text/javascript" language="javascript" src="js/loadInterface.js"></script>
</head>
<body>
  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container-fluid">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a href="./index.php" class="brand">UCMC Equipment QA</a>
        <div class="nav-collapse">
          <ul class="nav">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Linac
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="form_entry.php?action=new&form_id=1">Submit new record</a></li>
                  <li><a href="form_entry.php?action=index&form_id=1">View history</a></li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  CT
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="form_entry.php?action=new&form_id=2">Submit new record</a></li>
                  <li><a href="form_entry.php?action=index&form_id=2">View history</a></li>
                </ul>
              </li>
';
  if ($user->isAdmin($database)) {
  echo '              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Admin
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="form.php">Forms</a></li>
                  <li><a href="machine_type.php">Machine Types</a></li>
                  <li><a href="machine.php">Machines</a></li>
                  <li><a href="facility.php">Facilities</a></li>
                  <li><a href="#">Users</a></li>
                </ul>
              </li>
';
  }
  echo '          </ul>
          <ul class="nav pull-right">
            <li class="dropdown">
';
  if ($user->loggedIn($database)) {
    echo '              <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.escape_output($user->name).'<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <a href="/profile.php">Profile</a>
                <a href="/logout.php">Sign out</a>
              </ul>
';
  } else {
    echo '              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sign in<b class="caret"></b></a>
              <ul class="dropdown-menu">
';
    display_login_form();
    echo '              </ul>
            </li>
            <li>
              <a href="register.php">Register</a>
';
  }
  echo '            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
';
  if ($status != "") {
    echo '<div class="alert">
  <button class="close" data-dismiss="alert" href="#">×</button>
  '.escape_output($status).'
</div>
';
  }
}

function display_login_form() {
  echo '<form accept-charset="UTF-8" action="/login.php" method="post">
  <label for="Email">Email</label>
  <input id="username" name="username" size="30" type="text" />
  <label for="password">Password</label>
  <input id="password" name="password" size="30" type="password" />
  <input class="btn btn-small btn-primary" name="commit" type="submit" value="Sign in" />
</form>
';
}

function display_month_year_dropdown($select_id="", $select_name_prefix="form_entry", $selected=array(1,1)) {
  echo "<select id='".escape_output($select_id)."' name='".escape_output($select_name_prefix)."[qa_month]'>
";
  for ($month_i = 1; $month_i <= 12; $month_i++) {
    echo "  <option value='".$month_i."'".(($selected[0] === $month_i) ? "selected='selected'" : "").">".htmlentities(date('M', mktime(0, 0, 0, $month_i, 1, 2000)), ENT_QUOTES, "UTF-8")."</option>
";
  }
echo "</select>
<select id='".escape_output($select_id)."' name='".escape_output($select_name_prefix)."[qa_year]'>
";
  for ($year = 2007; $year <= intval(date('Y', time())); $year++) {
    echo "  <option value='".$year."'".(($selected[1] === $year) ? "selected='selected'" : "").">".$year."</option>
";
  }
echo "</select>
";
}

function display_facilities($database, $user) {
  //lists all facilities.
  if (!$user->isAdmin($database)) {
    echo "You must be an administrator to view facilities.";
  } else {
    echo "<table class='table table-striped table-bordered dataTable'>
  <thead>
    <tr>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
";
    $facilities = $database->stdQuery("SELECT `id`, `name` FROM `facilities` ORDER BY `id` ASC");
    while ($facility = mysqli_fetch_assoc($facilities)) {
      echo "    <tr>
      <td><a href='facility.php?action=edit&id=".intval($facility['id'])."'>".escape_output($facility['name'])."</a></td>
    </tr>
";
    }
    echo "  </tbody>
</table>
";
  }
}

function display_facility_dropdown($database, $select_id="facility_id", $selected=0) {
  echo "<select id='".escape_output($select_id)."' name='".escape_output($select_id)."'>
";
  $facilities = $database->stdQuery("SELECT `id`, `name` FROM `facilities`");
  while ($facility = mysqli_fetch_assoc($facilities)) {
    echo "  <option value='".intval($facility['id'])."'".(($selected == intval($facility['id'])) ? "selected='selected'" : "").">".escape_output($facility['name'])."</option>
";
  }
  echo "</select>
";
}

function display_facility_edit_form($database, $user, $id=false) {
  // displays a form to edit facility type parameters.
  if (!($id === false)) {
    $facilityObject = $database->queryFirstRow("SELECT * FROM `facilities` WHERE `id` = ".intval($id)." LIMIT 1");
    if (!$facilityObject) {
      $id = false;
    }
  }
  echo "<form action='facility.php".(($id === false) ? "" : "?id=".intval($id))."' method='POST' class='form-horizontal'>
".(($id === false) ? "" : "<input type='hidden' name='facility[id]' value='".intval($id)."' />")."
  <fieldset>
    <div class='control-group'>
      <label class='control-label' for='facility[name]'>Name</label>
      <div class='controls'>
        <input name='facility[name]' type='text' class='input-xlarge' id='facility[name]'".(($id === false) ? "" : " value='".escape_output($facilityObject['name'])."'").">
      </div>
    </div>
    <div class='form-actions'>
      <button type='submit' class='btn btn-primary'>".(($id === false) ? "Add Facility" : "Save changes")."</button>
      <a href='#' onClick='window.location.replace(document.referrer);' class='btn'>".(($id === false) ? "Go back" : "Discard changes")."</a>
    </div>
  </fieldset>
</form>
";
}

function display_register_form($database, $action=".") {
  echo '    <form class="form-horizontal" name="register" method="post" action="'.$action.'">
      <fieldset>
        <legend>Sign up</legend>
        <div class="control-group">
          <label class="control-label">Name</label>
          <div class="controls">
            <input type="text" class="" name="name" id="name" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Email</label>
          <div class="controls">
            <input type="text" class="" name="email" id="email" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Password</label>
          <div class="controls">
            <input type="password" class="" name="password" id="password" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Confirm password</label>
          <div class="controls">
            <input type="password" class="" name="password_confirmation" id="password_confirmation" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label">Facility</label>
          <div class="controls">
';
  echo display_facility_dropdown($database);
  echo '          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Sign up</button>
        </div>
      </fieldset>
    </form>
';
}

function display_ionization_chamber_dropdown($select_id = "form_entry_form_values_ionization_chamber", $select_name_prefix="form_entry[form_values]", $selected="") {
  echo "<select class='span12' id='".escape_output($select_id)."' name='".escape_output($select_name_prefix)."[ionization_chamber]'>
  <option value='Farmer (S/N 944, ND.SW(Gy/C) 5.18E+07)'".(($selected === 'Farmer (S/N 944, ND.SW(Gy/C) 5.18E+07)') ? "selected='selected'" : "").">Farmer (S/N 944, ND.SW(Gy/C) 5.18E+07)</option>
  <option value='Farmer (S/N 269, ND.SW(Gy/C) 5.32E+07)'".(($selected === 'Farmer (S/N 269, ND.SW(Gy/C) 5.32E+07)') ? "selected='selected'" : "").">Farmer (S/N 269, ND.SW(Gy/C) 5.32E+07)</option>
</select>
";
}

function display_electrometer_dropdown($select_id = "form_entry_form_values_electrometer", $select_name_prefix="form_entry[form_values]", $selected="") {
  echo "<select class='span12' id='".escape_output($select_id)."' name='".escape_output($select_name_prefix)."[electrometer]'>
  <option value='Keithley Model 614 (S/N 42215, Kelec 0.995)'".(($selected === 'Keithley Model 614 (S/N 42215, Kelec 0.995)') ? "selected='selected'" : "").">Keithley Model 614 (S/N 42215, Kelec 0.995)</option>
  <option value='SI CDX 2000B #1 (S/N J073443, Kelec 1.000)'".(($selected === 'SI CDX 2000B #1 (S/N J073443, Kelec 1.000)') ? "selected='selected'" : "").">SI CDX 2000B #1 (S/N J073443, Kelec 1.000)</option>
  <option value='SI CDX 2000B #2 (S/N J073444, Kelec 1.000)'".(($selected === 'SI CDX 2000B #2 (S/N J073444, Kelec 1.000)') ? "selected='selected'" : "").">SI CDX 2000B #2 (S/N J073444, Kelec 1.000)</option>
</select>
";  
}

function display_machine_types($database, $user) {
  //lists all machine types.
  if (!$user->isAdmin($database)) {
    echo "You must be an administrator to view machine types.";
  } else {
    echo "<table class='table table-striped table-bordered dataTable'>
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
";
    $machine_types = $database->stdQuery("SELECT `id`, `name`, `description` FROM `machine_types` ORDER BY `id` ASC");
    while ($machine_type = mysqli_fetch_assoc($machine_types)) {
      echo "    <tr>
      <td><a href='machine_type.php?action=show&id=".intval($machine_type['id'])."'>".escape_output($machine_type['name'])."</a></td>
      <td>".escape_output($machine_type['description'])."</td>
    </tr>
";
    }
    echo "  </tbody>
</table>
";
  }
}

function display_machine_type_dropdown($database, $select_id="machine_type_id", $selected=0) {
  echo "<select id='".escape_output($select_id)."' name='".escape_output($select_id)."'>
";
  $machineTypes = $database->stdQuery("SELECT `id`, `name` FROM `machine_types`");
  while ($machineType = mysqli_fetch_assoc($machineTypes)) {
    echo "  <option value='".intval($machineType['id'])."'".(($selected == intval($machineType['id'])) ? "selected='selected'" : "").">".escape_output($machineType['name'])."</option>
";
  }
  echo "</select>
";
}

function display_machine_type_edit_form($database, $user, $id=false) {
  // displays a form to edit machine type parameters.
  if (!($id === false)) {
    $machineTypeObject = $database->queryFirstRow("SELECT * FROM `machine_types` WHERE `id` = ".intval($id)." LIMIT 1");
    if (!$machineTypeObject) {
      $id = false;
    }
  }
  echo "<form action='machine_type.php".(($id === false) ? "" : "?id=".intval($id))."' method='POST' class='form-horizontal'>
  <fieldset>
    <div class='control-group'>
      <label class='control-label' for='machine_type[name]'>Name</label>
      <div class='controls'>
        <input name='machine_type[name]' type='text' class='input-xlarge' id='machine_type[name]'".(($id === false) ? "" : " value='".escape_output($machineTypeObject['name'])."'").">
      </div>
    </div>
    <div class='control-group'>
      <label class='control-label' for='machine_type[description]'>Description</label>
      <div class='controls'>
        <input name='machine_type[description]' type='text' class='input-xlarge' id='machine_type[description]'".(($id === false) ? "" : " value='".escape_output($machineTypeObject['description'])."'").">
      </div>
    </div>
    <div class='form-actions'>
      <button type='submit' class='btn btn-primary'>".(($id === false) ? "Add Machine Type" : "Save changes")."</button>
      <a href='#' onClick='window.location.replace(document.referrer);' class='btn'>".(($id === false) ? "Go back" : "Discard changes")."</a>
    </div>
  </fieldset>
</form>
";
}

function display_machine_type_info($database, $user, $machine_type_id, $graph_div_prefix = "machine_type_info") {
  $machineTypeObject = $database->queryFirstRow("SELECT * FROM `machine_types` WHERE `id` = ".intval($machine_type_id)." LIMIT 1");
  if (!$machineTypeObject) {
    echo "This machine_type does not exist. Please select another machine_type and try again.";
  } else {
    $machines = $database->stdQuery("SELECT `id`, `name` FROM `machines` WHERE `facility_id` = ".intval($user->facility_id)." AND `machine_type_id` = ".intval($machineTypeObject['id']));
    while ($machine = mysqli_fetch_assoc($machines)) {
      echo "<h2>".escape_output($machine['name'])."</h2>
";
      display_machine_info($database, $user, $machine['id'], $graph_div_prefix."_".$machine['id']);
    }
  }
}

function display_machines($database, $user) {
  //lists all machines.
  if (!$user->isAdmin($database)) {
    echo "You must be an administrator to view machines.";
  } else {
    echo "<table class='table table-striped table-bordered dataTable'>
  <thead>
    <tr>
      <th>Name</th>
      <th>Type</th>
      <th>Facility</th>
    </tr>
  </thead>
  <tbody>
";
    $machines = $database->stdQuery("SELECT `id`, `name`, `machine_type_id`, `facility_id` FROM `machines` ORDER BY `id` ASC");
    while ($machine = mysqli_fetch_assoc($machines)) {
      $facility = $database->queryFirstValue("SELECT `name` FROM `facilities` WHERE `id` = ".intval($machine['facility_id'])." LIMIT 1");
      $type = $database->queryFirstValue("SELECT `name` FROM `machine_types` WHERE `id` = ".intval($machine['machine_type_id'])." LIMIT 1");
      if (!$facility) {
        $facility = "None";
      }
      if (!$type) {
        $type = "None";
      }
      echo "    <tr>
      <td><a href='machine.php?action=show&id=".intval($machine['id'])."'>".escape_output($machine['name'])."</a></td>
      <td>".escape_output($type)."</td>
      <td>".escape_output($facility)."</td>
    </tr>
";
    }
    echo "  </tbody>
</table>
";
  }
}

function display_machine_dropdown($database, $user, $select_id="machine_id", $selected=0) {
  echo "<select id='".escape_output($select_id)."' name='".escape_output($select_id)."'>
";
  $machines = $database->stdQuery("SELECT `id`, `name` FROM `machines` WHERE `facility_id` = ".intval($user->facility_id));
  while ($machine = mysqli_fetch_assoc($machines)) {
    echo "  <option value='".intval($machine['id'])."'".(($selected == intval($machine['id'])) ? "selected='selected'" : "").">".escape_output($machine['name'])."</option>
";
  }
  echo "</select>
";
}

function display_machine_edit_form($database, $user, $id=false) {
  // displays a form to edit machine type parameters.
  if (!($id === false)) {
    $machineObject = $database->queryFirstRow("SELECT * FROM `machines` WHERE `id` = ".intval($id)." LIMIT 1");
    if (!$machineObject) {
      $id = false;
    }
  }
  $facility = $database->queryFirstValue("SELECT `name` FROM `facilities` WHERE `id` = ".intval($user->facility_id)." LIMIT 1");
  if (!$facility) {
    $facility = "None";
  }
  
  echo "<form action='machine.php".(($id === false) ? "" : "?id=".intval($id))."' method='POST' class='form-horizontal'>
".(($id === false) ? "" : "<input type='hidden' name='machine[id]' value='".intval($id)."' />")."
  <fieldset>
    <div class='control-group'>
      <label class='control-label' for='machine[name]'>Name</label>
      <div class='controls'>
        <input name='machine[name]' type='text' class='input-xlarge' id='machine[name]'".(($id === false) ? "" : " value='".escape_output($machineObject['name'])."'").">
      </div>
    </div>
    <div class='control-group'>
      <label class='control-label' for='machine[facility_id]'>Facility</label>
      <div class='controls'>
";
  display_machine_type_dropdown($database, "machine[machine_type_id]", ($id === false) ? 0 : $machineObject['machine_type_id']);
  echo "      </div>
    </div>
    <div class='control-group'>
      <label class='control-label' for='machine[facility_id]'>Facility</label>
      <div class='controls'>
        <input name='machine[facility_id]' value='".intval($user->facility_id)."' type='hidden' />
        <span class='input-xlarge uneditable-input'>".escape_output($facility)."</span>
      </div>
    </div>
    <div class='form-actions'>
      <button type='submit' class='btn btn-primary'>".(($id === false) ? "Add Machine" : "Save changes")."</button>
      <a href='#' onClick='window.location.replace(document.referrer);' class='btn'>".(($id === false) ? "Go back" : "Discard changes")."</a>
    </div>
  </fieldset>
</form>
";
}

function display_machine_info($database, $user, $machine_id, $graph_div_prefix = "machine_info") {
  $machineObject = $database->queryFirstRow("SELECT * FROM `machines` WHERE `id` = ".intval($machine_id)." LIMIT 1");
  if (!$machineObject) {
    echo "This machine does not exist. Please select another machine and try again.";
  } else {
    $i = 0;
    $machine_fields = $database->stdQuery("SELECT `form_fields`.`id`, `form_fields`.`name` FROM `form_entries` LEFT OUTER JOIN `form_fields` ON `form_fields`.`form_id` = `form_entries`.`form_id` WHERE `form_entries`.`machine_id` = ".intval($machine_id)." GROUP BY `form_fields`.`id` ORDER BY `form_fields`.`name` ASC");
    while ($machine_field = mysqli_fetch_assoc($machine_fields)) {
      $field_values = $database->queryAssoc("SELECT `form_values`.`value`, `form_entries`.`created_at` AS `date` FROM `form_values` LEFT OUTER JOIN `form_entries` ON `form_values`.`form_entry_id` = `form_entries`.`id` WHERE `form_values`.`form_field_id` = ".intval($machine_field['id'])." ORDER BY `form_entries`.`created_at` ASC");
      $field_strings = array();
      $field_labels = array();
      
      foreach ($field_values as $key => $field_value) {
        if (is_numeric($field_value[0])) {
          $field_strings[] = "{x: '".date('m/d/y', strtotime($field_value[1]))."', y: ".escape_output($field_value[0])."}";
        }
      }
      if (count($field_strings) > 1) {
        echo "<span id='".escape_output($graph_div_prefix)."_".intval($i)."'></span>
<script type='text/javascript'>
var data = [".implode(",", $field_strings)."];
displayFormFieldLineGraph(data, '".humanize($machine_field['name'])."', '".escape_output($graph_div_prefix)."_".intval($i)."');
</script>
";
        $i++;
      }
    }
  }
}

function display_forms($database) {
  //lists all forms.
  echo "<table class='table table-striped table-bordered dataTable'>
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
      <th>Machine Type</th>
    </tr>
  </thead>
  <tbody>
";
  $forms = $database->stdQuery("SELECT `forms`.`id`, `forms`.`name`, `forms`.`description`, `machine_types`.`name` AS `machine_type_name` FROM `forms` LEFT OUTER JOIN `machine_types` ON `forms`.`machine_type_id` = `machine_types`.`id` ORDER BY `forms`.`id` ASC");
  while ($form = mysqli_fetch_assoc($forms)) {
    echo "    <tr>
      <td><a href='form.php?action=edit&id=".intval($form['id'])."'>".escape_output($form['name'])."</a></td>
      <td>".escape_output($form['description'])."</td>
      <td>".escape_output($form['machine_type_name'])."</td>
    </tr>
";
  }
  echo "  </tbody>
</table>
";
}

function display_form_field_graph($database, $form_field) {
  $field_values = $database->queryAssoc("SELECT * FROM `form_values` WHERE `form_field_id` = ".intval($form_field['id']));
}

function display_form_history($database, $user, $form_id) {
  $formObject = $database->queryFirstRow("SELECT * FROM `forms` WHERE `id` = ".intval($form_id)." LIMIT 1");
  if (!$formObject) {
    echo "This form does not exist. Please select another form and try again.";
  } else {
    $form_fields = $database->stdQuery("SELECT `id`, `name` FROM `form_fields` WHERE `form_id` = ".intval($form_id));
    while ($form_field = mysqli_fetch_assoc($form_fields)) {
      display_form_field_graph($database, $form_field);
    }
  }
}

function display_form_edit_form($database, $user, $id=false) {
  // displays a form to edit form parameters.
  if (!$user->isAdmin($database)) {
    echo "Only administrators may edit forms.";
  } else {
    if (!($id === false)) {
      $formObject = $database->queryFirstRow("SELECT * FROM `forms` WHERE `id` = ".intval($id)." LIMIT 1");
      if (!$formObject) {
        $id = false;
      }
    }
    echo "<form action='form.php".(($id === false) ? "" : "?id=".intval($id))."' method='POST' class='form-horizontal'>
  <fieldset>
".(($id === false) ? "" : "<input type='hidden' name='form[id]' value='".intval($id)."' />")."
    <div class='control-group'>
      <label class='control-label' for='form[name]'>Name</label>
      <div class='controls'>
        <input name='form[name]' type='text' class='input-xlarge' id='form[name]'".(($id === false) ? "" : " value='".escape_output($formObject['name'])."'").">
      </div>
    </div>
    <div class='control-group'>
      <label class='control-label' for='form[description]'>Description</label>
      <div class='controls'>
        <input name='form[description]' type='text' class='input-xlarge' id='form[description]'".(($id === false) ? "" : " value='".escape_output($formObject['description'])."'").">
      </div>
    </div>
    <div class='control-group'>
      <label class='control-label' for='form[machine_type_id]'>Machine Type</label>
      <div class='controls'>
        ";
    display_machine_type_dropdown($database, "form[machine_type_id]", (($id === false) ? 0 : intval($formObject['machine_type_id'])));
    echo "      </div>
    </div>
    <div class='control-group'>
      <label class='control-label' for='form[js]'>Javascript</label>
      <div class='controls'>
        <textarea class='input-xlarge' id='form[js]' name='form[js]' cols='500' rows='10'>".(($id === false) ? "" : escape_output($formObject['js']))."</textarea>
      </div>
    </div>
    <div class='control-group'>
      <label class='control-label' for='form[php]'>PHP</label>
      <div class='controls'>
        <textarea class='input-xlarge' id='form[php]' name='form[php]' cols='500' rows='10'>".(($id === false) ? "" : escape_output($formObject['php']))."</textarea>
      </div>
    </div>
    <div class='form-actions'>
      <button type='submit' class='btn btn-primary'>".(($id === false) ? "Create form" : "Save changes")."</button>
      <a href='#' onClick='window.location.replace(document.referrer);' class='btn'>".(($id === false) ? "Go back" : "Discard changes")."</a>
    </div>
  </fieldset>
</form>
";
  }
}

function display_form_entries($database, $user, $form_id=false) {
  //lists all form_entries.
  echo "<table class='table table-striped table-bordered dataTable'>
  <thead>
    <tr>
      <th>Form</th>
      <th>Machine</th>
      <th>User</th>
      <th>QA Month</th>
      <th>Submitted on</th>
      <th>Comments</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
";
  if (is_numeric($form_id)) {
    $form_id = intval($form_id);
  } else {
    $form_id = "`form_entries`.`form_id`";
  }
  $form_entries = $database->stdQuery("SELECT `form_entries`.`id`, `form_entries`.`form_id`, `forms`.`name` AS `form_name`, `form_entries`.`machine_id`, `machines`.`name` AS `machine_name`, `form_entries`.`user_id`, `users`.`name` AS `user_name`, `created_at`, `qa_month`, `qa_year`, `comments` FROM `form_entries` LEFT OUTER JOIN `forms` ON `forms`.`id` = `form_entries`.`form_id` LEFT OUTER JOIN `machines` ON `machines`.`id` = `form_entries`.`machine_id` LEFT OUTER JOIN `users` ON `users`.`id` = `form_entries`.`user_id` WHERE `form_entries`.`form_id` = ".$form_id." ORDER BY `id` ASC");
  while ($form_entry = mysqli_fetch_assoc($form_entries)) {
    echo "    <tr>
      <td><a href='form.php?action=show&id=".intval($form_entry['form_id'])."'>".escape_output($form_entry['form_name'])."</a></td>
      <td><a href='machine.php?action=show&id=".intval($form_entry['machine_id'])."'>".escape_output($form_entry['machine_name'])."</a></td>
      <td><a href='user.php?action=show&id=".intval($form_entry['user_id'])."'>".escape_output($form_entry['user_name'])."</a></td>
      <td>".intval($form_entry['qa_year'])."/".intval($form_entry['qa_month'])."</td>
      <td>".escape_output(date('n/j/Y', strtotime($form_entry['created_at'])))."</td>
      <td>".escape_output($form_entry['comments'])."</td>
      <td><a href='form_entry.php?action=edit&id=".intval($form_entry['id'])."'>Edit</a></td>
      <td></td>
    </tr>
";
  }
  echo "  </tbody>
</table>
";
}

function display_form_entry_edit_form($database, $user, $id=false, $form_id=false) {
  // displays a form to edit form parameters.
  if (!($id === false)) {
    $formEntryObject = $database->queryFirstRow("SELECT * FROM `form_entries` WHERE `id` = ".intval($id)." LIMIT 1");
    if (!$formEntryObject) {
      $id = false;
      $form_id = false;
    } else {
      $form_id = $formEntryObject['form_id'];
    }
  }
  if (!($form_id === false)) {
    $formObject = $database->queryFirstRow("SELECT * FROM `forms` WHERE `id` = ".intval($form_id)." LIMIT 1");
    if (!$formObject) {
      $form_id = false;
    }
  }
  if ($form_id === false) {
    echo "Please specify a valid form entry ID or form ID.";
    return;
  }
  if (!($id === false)) {
    $formValues = $database->stdQuery("SELECT * FROM `form_values` WHERE `form_entry_id` = ".intval($id));
    while ($formValue = mysqli_fetch_assoc($formValues)) {
      $formField = $database->queryFirstValue("SELECT `name` FROM `form_fields` WHERE `id` = ".intval($formValue['form_field_id'])." LIMIT 1");
      if ($formField) {
        $formEntryObject['form_values'][$formField] = $formValue['value'];
      }
    }
  }
  if ($formObject['php'] != '') {
    eval($formObject['php']);
  }
  if ($formObject['js'] != '') {
    echo "<script type='text/javascript'>
  ".$formObject['js']."
</script>
";
  }
}

function display_footer() {
  echo '    <hr />
  </div>
</body>
</html>';
}

?>