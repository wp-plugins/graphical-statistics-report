<?php

/*
Plugin Name: graphical statistics report
Plugin URI: http://gopi.coolpage.biz/demo/2009/08/01/graphical-statistics-report/
Description: This plug-in will display the graphical report for admin about, monthly user registration, monthly post summary, monthly comments posted summary and category wise post summary.
Version: 1.0
Author: Gopi R
Author URI: http://gopi.coolpage.biz/demo/2009/08/01/graphical-statistics-report/
*/

function graphic_report_deactivate() 
{

}
function graphic_report_activation()
{

}
function graphic_report_admin_options() 
{
	global $wpdb;
	$siteurl = get_option('siteurl');
	$pluginurl = "/wp-content/plugins/graphical-statistics-report";
	$fullpluginurl = $siteurl.$pluginurl;
	
	$title = __('Graphical statistics');
	$title_posts = __('Monthly post report');
	$title_users = __('Monthly user registration report');
	$title_category = __('Category report');
	$title_comment = __('Monthly comment report');
	?>
    <SCRIPT LANGUAGE="Javascript" SRC="<?php echo $fullpluginurl; ?>/FusionCharts.js"></SCRIPT>
    <?php
	include("graphical-statistics-charts.php");
	?>
    <div class="wrap">
    <h2><?php echo wp_specialchars( $title ); ?></h2>
    <h5><?php echo wp_specialchars( $title_posts ); ?></h5>
    </div>
	<?php


	//------------------------------Monthly post Summary-----------------------------------------------------
	$sSql =	"SELECT MONTH(post_date) as m, YEAR(post_date) as y, COUNT(*) as tot";
	$sSql =	$sSql . " FROM $wpdb->posts where post_type = 'post'";
	$sSql =	$sSql . " GROUP BY MONTH(post_date), YEAR(post_date) order by YEAR(post_date) desc,MONTH(post_date) desc limit 0,12";
	$data = $wpdb->get_results($sSql);
	$i = 0;
	$graph_ststus=0;
	$arrposts = array();
	$monthnames = array(1 => 'January',2 => 'February',3 => 'March',4 => 'April',5 => 'May',6 => 'June',7 => 'July',8 => 'August',9 => 'September',10 => 'October',11 => 'November',12 => 'December');
    foreach ( $data as $data ) 
	{ 
		$arrposts[$i][1] = $monthnames[$data->m];
		$arrposts[$i][2] = $data->tot;
		$arrposts[$i][3] = $data->y;
		$i = $i+1; 
	} 
	if($i > 0) { $graph_ststus = 1; }
	$strXML = "<graph caption='Monthly post summary' subcaption='This will display the post report for last 12 months' xAxisName='Month' yAxisMinValue='0' yAxisName='Post total for every month' decimalPrecision='0' formatNumberScale='0' numberPrefix=' ' showNames='1' showValues='0' showAlternateHGridColor='1' AlternateHGridColor='bbd8e7' canvasBorderColor='ECF4F9' baseFontColor='1A5873' lineColor='2EA0D1' divLineColor='8cbdd5' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1'>";
	foreach ($arrposts as $arSubData)
	{
		$strXML = $strXML . "<set name='" . $arSubData[1] . " " . $arSubData[3] . "' value='" . $arSubData[2] ."' hoverText='" . $arSubData[1] . "' />";
	}
	$strXML = $strXML . "</graph>";
	if($graph_ststus==1)
	{
		echo renderChart("$fullpluginurl/FCF_Line.swf", "", $strXML, "wp_posts", 800, 350, false, false);
	}
	else
	{
		echo "<div align='center'>At present monthly post summary graph not available.</div>";
	}
	//---------------------------------------------------------------------------------------------------------


	?><div class="wrap"><h5><?php echo wp_specialchars( $title_comment ); ?></h5></div><?php
	//------------------------------Monthly comment Summary-------------------------------------------------------
	$sSql =	"SELECT  MONTH(comment_date) as m, YEAR(comment_date) as y, COUNT(*) as tot from $wpdb->comments WHERE ";
	$sSql =	$sSql . " comment_type<>'pingback' GROUP BY MONTH(comment_date), YEAR(comment_date) order by ";
	$sSql =	$sSql . " YEAR(comment_date) desc,MONTH(comment_date) desc limit 0,12";
	$data = $wpdb->get_results($sSql);
	$i = 0;
	$graph_ststus=0;
	$arrcomment = array();
	foreach ( $data as $data ) 
	{ 
		$arrcomment[$i][1] = $monthnames[$data->m];
		$arrcomment[$i][2] = $data->tot;
		$arrcomment[$i][3] = $data->y;
		$i = $i+1; 
	} 
	if($i > 0) { $graph_ststus = 1; }
	$strXML = "<graph caption='Monthly comment report' subcaption='This will display the comment posted count for last 12 month' xAxisName='Month' yAxisMinValue='0' yAxisName='post count for each category' decimalPrecision='0' formatNumberScale='0' numberPrefix=' ' showNames='1' showValues='0' showAlternateHGridColor='1' AlternateHGridColor='bbd8e7' canvasBorderColor='ECF4F9' baseFontColor='1A5873' lineColor='2EA0D1' divLineColor='8cbdd5' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1'>";
	foreach ($arrcomment as $arSubData)
	{
		$strXML = $strXML . "<set name='" . $arSubData[1] . " " . $arSubData[3] . "' value='" . $arSubData[2] ."' hoverText='" . $arSubData[1] . "' />";
	}
	$strXML = $strXML . "</graph>";
	if($graph_ststus==1)
	{
		echo renderChart("$fullpluginurl/FCF_Line.swf", "", $strXML, "arrcomment", 800, 350, false, false);
	}
	else
	{
		echo "<div align='center'>At present monthly comment report graph not available.</div>";
	}
	//----------------------------------------------------------------------------------------------------------


	?><div class="wrap"><h5><?php echo wp_specialchars( $title_users ); ?></h5></div><?php
	//------------------------------Monthly post Summary-------------------------------------------------------
	$sSql =	"SELECT MONTH( user_registered ) AS m, YEAR( user_registered ) AS y, COUNT( * ) AS tot";
	$sSql =	$sSql . " FROM $wpdb->users";
	$sSql =	$sSql . " GROUP BY MONTH( user_registered ) , YEAR( user_registered )";
	$sSql =	$sSql . " ORDER BY YEAR( user_registered ) DESC , MONTH( user_registered ) DESC limit 0,12";
	$data = $wpdb->get_results($sSql);
	$i = 0;
	$graph_ststus=0;
	$arrusers = array();
	foreach ( $data as $data ) 
	{ 
		$arrusers[$i][1] = $monthnames[$data->m];
		$arrusers[$i][2] = $data->tot;
		$arrusers[$i][3] = $data->y;
		$i = $i+1; 
	} 
	if($i > 0) { $graph_ststus = 1; }
	$strXML = "<graph caption='Monthly user registration summary' subcaption='This will display the last 12 months user registration summary' xAxisName='Month' yAxisMinValue='0' yAxisName='User count for every month' decimalPrecision='0' formatNumberScale='0' numberPrefix=' ' showNames='1' showValues='0' showAlternateHGridColor='1' AlternateHGridColor='bbd8e7' canvasBorderColor='ECF4F9' baseFontColor='1A5873' lineColor='2EA0D1' divLineColor='8cbdd5' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1'>";
	foreach ($arrusers as $arSubData)
	{
		$strXML = $strXML . "<set name='" . $arSubData[1] . " " . $arSubData[3] . "' value='" . $arSubData[2] ."' hoverText='" . $arSubData[1] . "' />";
	}
	$strXML = $strXML . "</graph>";
	if($graph_ststus==1)
	{
		echo renderChart("$fullpluginurl/FCF_Line.swf", "", $strXML, "wp_users", 800, 350, false, false);
	}
	else
	{
		echo "<div align='center'>At present monthly user registration summary graph not available.</div>";
	}
	//----------------------------------------------------------------------------------------------------------


	?><div class="wrap"><h5><?php echo wp_specialchars( $title_category ); ?></h5></div><?php
	//------------------------------Category Summary-------------------------------------------------------
	$sSql =	"SELECT a.count as tot ,b.name as name FROM $wpdb->term_taxonomy as a,$wpdb->terms as b WHERE a.term_id=b.term_id" ;
	$sSql =	$sSql . " and a.taxonomy='category' order by a.term_taxonomy_id limit 0,20";
	$data = $wpdb->get_results($sSql);
	$i = 0;
	$graph_ststus=0;
	$arrcategory = array();
	foreach ( $data as $data ) 
	{ 
		$arrcategory[$i][1] = $data->name;
		$arrcategory[$i][2] = $data->tot;
		$i = $i+1; 
	} 
	if($i > 0) { $graph_ststus = 1; }
	$strXML = "<graph caption='Category report' subcaption='This will display post count for each category' xAxisName='category' yAxisMinValue='0' yAxisName='post count for each category' decimalPrecision='0' formatNumberScale='0' numberPrefix=' ' showNames='1' showValues='0' showAlternateHGridColor='1' AlternateHGridColor='bbd8e7' canvasBorderColor='ECF4F9' baseFontColor='1A5873' lineColor='2EA0D1' divLineColor='8cbdd5' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1'>";
	foreach ($arrcategory as $arSubData)
	{
		$strXML = $strXML . "<set name='" . $arSubData[1] . "' value='" . $arSubData[2] ."' hoverText='" . $arSubData[1] . "' />";
	}
	$strXML = $strXML . "</graph>";
	if($graph_ststus==1)
	{
		echo renderChart("$fullpluginurl/FCF_Line.swf", "", $strXML, "category", 800, 350, false, false);
	}
	else
	{
		echo "<div align='center'>At present category report graph not available.</div>";
	}
	echo "<br><br>";
	//----------------------------------------------------------------------------------------------------------
	?>
    <br>Plug-in created by <a target="_blank" href='http://gopi.coolpage.biz/demo/about/'>Gopi</a>.<br>
    Thanks to fusion Charts team for this SWF graph.<br>
    <a target="_blank" href='http://gopi.coolpage.biz/demo/2009/08/01/graphical-statistics-report/'>click here</a> to post suggestion/comments about this plugin.<br>
	<a target="_blank" href='http://gopi.coolpage.biz/demo/'>click here</a> to see my plugin demo.<br>
	<a target="_blank" href='http://wordpress.org/extend/plugins/profile/gopiplus'>click here</a> to download my other plugin.<br><br>
    <?php
}

function graphic_report_add_to_menu() 
{
	add_options_page('Graphical Statistics', 'Graphical Statistics', 7, __FILE__, 'graphic_report_admin_options' );
}

register_activation_hook(__FILE__, 'graphic_report_activation');
add_action('admin_menu', 'graphic_report_add_to_menu');
register_deactivation_hook( __FILE__, 'graphic_report_deactivate' );

?>