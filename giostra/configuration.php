<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  includes
 */

require_once "includes/DbAbLayer/vendor/autoload.php";

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query\Expr;



Config::SetNameSite("Uuoden Web Framework");

Config::SetRootSite("/giostra");

Config::SetLanguage("it");

Config::SetPrefixTable("giostra_");

Config::SetTemplateSystem("AdminBSBM");

Config::SetTemplatePublic("NewsFeed");

Config::SetAccountMail("smtp.gmail.com",
					   465,
					   "ssl",
					   true,
					   "simplestnote@gmail.com",
					   "simplestnote14");

Config::SetConnectDB('giostra',
					 'root',
					 'root',
					 'localhost',
					 'pdo_mysql');










