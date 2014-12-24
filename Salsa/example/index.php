<?php 
//Load Template System
include('../salsa.php'); 

//Variables that can be used when genorating the page...
$vars = array();
$vars['title'] = "Salsa Template Example Usage";
$vars['heading'] = "An Example Page";
$vars['copyright'] = '&copy;' . date("Y");

//..add as many as you need or like
$vars['hello_world'] = "Hello World, this is the Salsa Template System.";

//You can genorate individual files relative to the site root and store the resulting HTML in a variable....
$vars['content'] = render_template('content.inc.php', $vars); 

//..Or a file that is part of the current active theme by wrapping the file name inside the function "template_file"
//For example, we can genorate a navigation bar
$vars['navigation'] = render_template(template_file("nav.tpl.php"), $vars); 

//Now we render the full page.
$page = render_template(template_file("page.tpl.php"), $vars);

//Print the genorated HTML
print $page;
?>