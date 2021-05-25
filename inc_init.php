<?php
namespace PHPMaker2021\inplaze;

use PHPMaker2021\inplaze\{UserProfile, Language, AdvancedSecurity, Timer, HttpErrorHandler};
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Container\ContainerInterface;
// use DI\Container as Container;
// use DI\ContainerBuilder;
// use Selective\SameSiteCookie\SameSiteCookieConfiguration;
// use Selective\SameSiteCookie\SameSiteCookieMiddleware;
// use Selective\SameSiteCookie\SameSiteSessionMiddleware;
// use Slim\Factory\AppFactory;
// use Slim\Factory\ServerRequestCreatorFactory;
// use Slim\Exception\HttpInternalServerErrorException;
// use Nyholm\Psr7\Factory\Psr17Factory;
// use Nyholm\Psr7Server\ServerRequestCreator;

// Relative path
$RELATIVE_PATH = "";

// Require files
require_once "in-plaze/vendor/autoload.php";
require_once "in-plaze/src/constants.php";
require_once "in-plaze/src/config.php";
require_once "in-plaze/src/phpfn.php";
require_once "in-plaze/src/userfn.php";

// Environment
$isProduction = IsProduction();
$isDebug = IsDebug();

// Warnings and notices as errors
if ($isDebug && Config("REPORT_ALL_ERRORS")) {
    error_reporting(E_ALL);
    set_error_handler(function ($severity, $message, $file, $line) {
        if (error_reporting() & $severity) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        }
    });
}

session_start();

if (ExecuteScalar("SELECT COUNT(*) FROM user WHERE Username = 'demo' AND Password = 'demo'") == 1 AND ((Security() != NULL AND !IsLoggedIn()) OR Session(SESSION_STATUS) != "login")) {
    $_SESSION[PROJECT_NAME . "_Username"] = 'demo';
    $_SESSION[PROJECT_NAME . "_Password"] = 'demo';
    $redirect = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME);
    header("location:in-plaze/login?redirect={$redirect}");
}

ob_start();
?>
