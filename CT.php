<? 
include_once("global/includes.php");
if(!session_is_registered(username)){
header("location:login.html");
}
start_html();
?>
<div id="sample-container">
		 <img src="images/title.gif"  />
 <form id="form1" action="logout.php" name="form1" method="post" >
<p align="right">
    <input type="submit" value="LOGOUT"  style="width:25%; float: right;"/>
</p>
<p align="right">
</p>
Welcome,<?php
echo $_SESSION["username"];
?>!
<p>&nbsp;</p>

</form>

<div id="layout-two-fixed-spread">
<div id="head-container">
<div id="header">
<h1>Machine QA Management</h1>
</div>
</div>
<div id="navigation-container">
<div id="navigation">

<ul>
<li><a href="index.php">HOME</a></li>		
<li><a href="CT_Br.php">CT Brilliance</a></li>
<li><a href="CT_Ac.php">CT AcQSim</a></li>
<li><a href="CT_working_history.php">CT History List</a></li>
<li><a href="Help/CT_QA_Procedure.pdf">HELP</a></li>
<li><a href="#">Contact us</a></li>
</ul>
</div>
</div>
<div id="content-container">
<div id="content-container2">
<div id="content-container3">
<div id="content">
<h2>Page heading</h2>

</div>
<div id="aside">
<h3>Aside heading</h3>
</div>
</div>
</div>
<?php
display_footer();
?>