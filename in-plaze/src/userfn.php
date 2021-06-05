<?php

namespace PHPMaker2021\inplaze;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

// Filter for 'Last Month' (example)
function GetLastMonthFilter($FldExpression, $dbid = 0)
{
    $today = getdate();
    $lastmonth = mktime(0, 0, 0, $today['mon'] - 1, 1, $today['year']);
    $val = date("Y|m", $lastmonth);
    $wrk = $FldExpression . " BETWEEN " .
        QuotedValue(DateValue("month", $val, 1, $dbid), DATATYPE_DATE, $dbid) .
        " AND " .
        QuotedValue(DateValue("month", $val, 2, $dbid), DATATYPE_DATE, $dbid);
    return $wrk;
}

// Filter for 'Starts With A' (example)
function GetStartsWithAFilter($FldExpression, $dbid = 0)
{
    return $FldExpression . Like("'A%'", $dbid);
}

// Global user functions
function Database_Connecting(&$info)
{
	if (!IsLocal()) {
		$username = strtok($_SERVER['SERVER_NAME'], ".");
		$custom = [
			'terracethaimassage.com.au' => [ 'username' => 'terracethai' ],
			'demo2.terracethaimassage.com.au' => [ 'username' => 'terracet', 'password' => 'zZfWUGD_Sj(7' ],
			'demo.dailyciousbakery.com' => [ 'username' => 'dailycious'],
			'dailyciousbakery.com' => [ 'username' => 'dailycious'],
			'v2020.in-plaze.com' => [ 'username' => 'example'],
			'sah.in-plaze.com' => [ 'username' => 'zf'],
			'in-plaze.com' => [ 'username' => 'inplaze'],
			'awedger.com' => [ 'username' => 'awedger'],
		];
		if (!empty($custom[$_SERVER['SERVER_NAME']])) {
			if (!empty($custom[$_SERVER['SERVER_NAME']]['username']))
				$username = $custom[$_SERVER['SERVER_NAME']]['username'];
			if (!empty($custom[$_SERVER['SERVER_NAME']]['password']))
				$password = $custom[$_SERVER['SERVER_NAME']]['password'];
		}
		$info["host"] = "localhost";
		$info["db"] = "{$username}_db";
		$info["user"] = "{$username}_root";
		$info["pass"] = empty($password) ? "test-2011" : $password;
	}
}

function Database_Connected(&$conn)
{
	try {
		if (empty($conn->executeQuery('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN))) {
			die ('Please check table in database');
		} else if (!in_array('contents', $conn->executeQuery('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN))) {
			$conn->executeQuery('RENAME TABLE `content` TO `contents`;');
		} else if ($conn->executeQuery("SELECT CASE WHEN COUNT(*) = 0 THEN 1 WHEN Permission = 0 THEN 1 ELSE 0 END FROM `user_level_permission` WHERE `User_Level_ID` = -2 AND `Table_Name` LIKE '%page.php%'")->fetchAll(\PDO::FETCH_COLUMN)[0]) {
			die ('Please check web page permission for anonymous in <a href="userpriv.php?User_Level_ID=-2">here</a>');
		} else if (!in_array('Intro', $conn->executeQuery('SHOW COLUMNS FROM product')->fetchAll(\PDO::FETCH_COLUMN))) {
			$conn->executeQuery('ALTER TABLE `product` ADD `Intro` TEXT NULL AFTER `Name`;');
		} else if (!in_array('Description', $conn->executeQuery('SHOW COLUMNS FROM category')->fetchAll(\PDO::FETCH_COLUMN))) {
			$conn->executeQuery('ALTER TABLE `category` ADD `Description` TEXT NULL AFTER `Name`;');
		}
	} catch (\PDOException $e) {
		die($e->getMessage());
	} catch (\Exception $e) {
		die($e->getMessage());
	}
}

function MenuItem_Adding($item)
{
    //var_dump($item);
    // Return false if menu item not allowed
    return true;
}

function Menu_Rendering($menu)
{
    // Change menu items here
}

function Menu_Rendered($menu)
{
    // Clean up here
}

// Page Loading event
function Page_Loading()
{
    //Log("Page Loading");
}

// Page Rendering event
function Page_Rendering()
{
    //Log("Page Rendering");
}

function Page_Unloaded() {
	if (!empty($_SERVER['HTTP_REFERER']))
		$_SESSION['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
}

// AuditTrail Inserting event
function AuditTrail_Inserting(&$rsnew)
{
    //var_dump($rsnew);
    return true;
}

// Personal Data Downloading event
function PersonalData_Downloading(&$row)
{
    //Log("PersonalData Downloading");
}

// Personal Data Deleted event
function PersonalData_Deleted($row)
{
    //Log("PersonalData Deleted");
}

// Route Action event
function Route_Action($app)
{
    // Example:
    // $app->get('/myaction', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
    // $app->get('/myaction2', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction2"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
}

// API Action event
function Api_Action($app)
{
    // Example:
    // $app->get('/myaction', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
    // $app->get('/myaction2', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction2"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
}

// Container Build event
function Container_Build($builder)
{
    // Example:
    // $builder->addDefinitions([
    //    "myservice" => function (ContainerInterface $c) {
    //        // your code to provide the service, e.g.
    //        return new MyService();
    //    },
    //    "myservice2" => function (ContainerInterface $c) {
    //        // your code to provide the service, e.g.
    //        return new MyService2();
    //    }
    // ]);
}
if ("Global Code") {
	$htaccess = 'in-plaze/.htaccess';
	$prepend_append = "\r\n\r\nphp_value auto_prepend_file none\r\nphp_value auto_append_file none";
	if (file_exists($htaccess) AND strpos(file_get_contents($htaccess), $prepend_append) === FALSE) {
		file_put_contents($htaccess, $prepend_append, FILE_APPEND);
		// ob_end_clean();
		// header("location:{$_SERVER['REQUEST_URI']}");
		// exit();
	}
	$htaccess = 'css/.htaccess';
	if (!file_exists($htaccess) OR strpos(file_get_contents($htaccess), $prepend_append) === FALSE) {
		file_put_contents($htaccess, $prepend_append, FILE_APPEND);
		// die('if');
		// ob_end_clean();
		// header("location:{$_SERVER['REQUEST_URI']}");
		// exit();
	}

	function notify ($token, $message) {
		// test and take from Postman
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://notify-api.line.me/api/notify",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => http_build_query(array('message' => $message)),
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer {$token}",
				"Content-Type: application/x-www-form-urlencoded"
			),
            CURLOPT_SSL_VERIFYPEER => false,
		));
		$response = curl_exec($curl);
		curl_close($curl);
		if ($response === FALSE)
			die(curl_error($curl));
		else if ($response = json_decode($response, true) AND $response['status'] != 200)
			die($response['message']);
	}

	/*READ AND NOTIFY LASTEST PHP ERRORS THEN CLEAR*/
	$API_ACTIONS['error_notify'] = function() {
		if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/PHP_errors.log") === true) {
			$error_content = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/PHP_errors.log");
			$error_content = explode("\n", $error_content);
			foreach (array_slice(array_unique($error_content), 0, 30) as $error) {
				if (!empty($error)) {
					$token = 'INsyhIumtbh1IgZILvyoyUTGiPfGXWpz9FebewTDnJx'; // Joe LINE Notify
					notify($token, print_r([
						'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'],
						'Error_Message' => $error
					], 1));
				}
			}
			file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/PHP_errors.log", "");
		}
	};
	$API_ACTIONS["error_notify"]();
	/*CAN MOVE TO BE CRONJOB*/
	/*https://domain.com/api/?action=error_notify*/
	function slugify ($string) {
		$string = preg_replace("`\[.*\]`U", "", $string);
		$string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $string);
		$string = str_replace('%', '-percent', $string);
		$string = htmlentities($string, ENT_COMPAT, 'utf-8');
		$string = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string );
		$string = preg_replace(array("`[^a-z0-9ก-๙เ-า]`i", "`[-]+`") , "-", $string);
		return $string;
	}
	function ip_get_page_id () {
		$page_id_string = basename($_SERVER['SCRIPT_NAME'], ".php");
		$page_id_string .= empty($_GET['main']) ? "" : " {$_GET['main']}";
		$page_id_string .= empty($_GET['sub']) ? "" : " {$_GET['sub']}";
		return slugify(trim($page_id_string));
	}
	function ip_get_element_id ($name, $global = FALSE) {
		if ($global)
			return mb_substr(slugify(trim($name)), 0, 100);
		else
			return ip_get_page_id() . ":" . mb_substr(slugify(trim($name)), 0, 100);
	}
	function ip_get_image_search() {
		if (!empty($_SESSION[PROJECT_NAME . '_image_advsrch_x_Name']))
			return $_SESSION[PROJECT_NAME . '_image_advsrch_x_Name'];
	}
	function ip_get_text_search() {
		if (!empty($_SESSION[PROJECT_NAME . '_text_advsrch_x_Name']))
			return $_SESSION[PROJECT_NAME . '_text_advsrch_x_Name'];
	}
	function ip_get_content_search() {
		if (!empty($_SESSION[PROJECT_NAME . '_contents_advsrch_x_Name']))
			return $_SESSION[PROJECT_NAME . '_contents_advsrch_x_Name'];
	}
	function get_p_child_tags () {
		return '<a><abbr><area><audio><b><bdi><bdo><br><button><canvas><cite><code><command><datalist><del><dfn><em><embed><i><iframe><img><input><ins><kbd><keygen><label><map><mark><math><meter><noscript><object><output><progress><q><ruby><s><samp><script><select><small><span><strong><sub><sup><svg><textarea><time><u><var><video><wbr><text>';
	}
}
