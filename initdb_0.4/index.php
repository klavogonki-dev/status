<?php

try {
	$db = new mysqli("localhost", "root", "root");
} catch (mysqli_sql_exception $e) {
	die("Need mysql on localhost user:root, pass:root");
}
$db->query("CREATE DATABASE IF NOT EXISTS kgru");


echo "todo: create table status: id | name | title | color | customCSS | since | until | ranglink | accesses | icon <br>";
$statuses = array(
	"admin" => array("title" => "Клавомеханик", "accesses" => "full"),
	"org" => array("title" => "Организатор" , "color" => "orange", "accesses" => "view_stat,create_compn,allow_msg"),
	"alt500" => array("title" => "Мастер", "ranglink" => "Маньяк"),
	"vip" => array("title" => "VIP", "icon" => "data:image/svg+xml;base64, PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxMi4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAgLS0+DQo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiIFsNCgk8IUVOVElUWSBuc19mbG93cyAiaHR0cDovL25zLmFkb2JlLmNvbS9GbG93cy8xLjAvIj4NCgk8IUVOVElUWSBuc19zdmcgImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4NCgk8IUVOVElUWSBuc194bGluayAiaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+DQpdPg0KPHN2ZyAgdmVyc2lvbj0iMS4xIiB4bWxucz0iJm5zX3N2ZzsiIHhtbG5zOnhsaW5rPSImbnNfeGxpbms7IiB4bWxuczphPSJodHRwOi8vbnMuYWRvYmUuY29tL0Fkb2JlU1ZHVmlld2VyRXh0ZW5zaW9ucy8zLjAvIg0KCSB3aWR0aD0iMTI5IiBoZWlnaHQ9IjEyNCIgdmlld0JveD0iLTAuNyAtMC4yIDEyOSAxMjQiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgLTAuNyAtMC4yIDEyOSAxMjQiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGRlZnM+DQo8L2RlZnM+DQo8cG9seWdvbiBvcGFjaXR5PSIwLjU3IiBzdHJva2U9IiMwMDAwMDAiIHBvaW50cz0iNjUuMSw2LjUgODQsNDQuOCAxMjYuMyw1MC45IDk1LjcsODAuNyAxMDIuOSwxMjIuOCA2NS4xLDEwMyAyNy4zLDEyMi44IA0KCTM0LjUsODAuNyAzLjksNTAuOSA0Ni4yLDQ0LjggIi8+DQo8cG9seWdvbiBmaWxsPSIjRkZENDMxIiBzdHJva2U9IiNCRjAwMDAiIHN0cm9rZS13aWR0aD0iMyIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBwb2ludHM9IjYyLjcsMS41IA0KCTgxLjYsMzkuOCAxMjMuOSw0NS45IDkzLjMsNzUuOCAxMDAuNSwxMTcuOSA2Mi43LDk4IDI0LjksMTE3LjkgMzIuMSw3NS44IDEuNSw0NS45IDQzLjgsMzkuOCAiLz4NCjxnPg0KCTxsaW5lYXJHcmFkaWVudCBpZD0iWE1MSURfMV8iIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiB4MT0iNjIuNjc2OCIgeTE9IjE0LjA1NzYiIHgyPSI2Mi42NzY4IiB5Mj0iMTA1Ljg5NzkiPg0KCQk8c3RvcCAgb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojRkZGRkZGIi8+DQoJCTxzdG9wICBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiNGRkRCMDAiLz4NCgk8L2xpbmVhckdyYWRpZW50Pg0KCTxwYXRoIGZpbGw9InVybCgjWE1MSURfMV8pIiBkPSJNNDcuOCw0NC4zYzAsMC0yNC45LDMuNi0zMy40LDQuOGM2LjIsNiwyNC4xLDIzLjUsMjQuMSwyMy41cy00LjIsMjQuOC01LjcsMzMuMg0KCQljNy42LTQsMjkuOC0xNS43LDI5LjgtMTUuN3MyMi4yLDExLjcsMjkuOCwxNS43Yy0xLjUtOC41LTUuNy0zMy4yLTUuNy0zMy4yczE4LTE3LjUsMjQuMS0yMy41Yy04LjUtMS4yLTMzLjQtNC44LTMzLjQtNC44DQoJCVM2Ni41LDIxLjgsNjIuNywxNC4xQzU4LjksMjEuOCw0Ny44LDQ0LjMsNDcuOCw0NC4zeiIvPg0KPC9nPg0KPC9zdmc+DQo="),
	"personal668817" => array("title" => "Титан", "color" => "#000000", "customCSS" => "font-weight: bold"),
	"foolsday" => array("customCSS" => "transform:rotate(180deg)", "since" => 1680296400, "until" => 1680382800)
);
echo "todo: load \$statuses to table status <br>";


echo "todo: create table userstatus: id | user_id | status | enabled <br>";
$userstatuses = array(
	"admin" => array(21, 82885, 123190, 474104),
	"alt500" => array(217625),
	"vip" => array(73879),
	"personal668817" => array(668817),
	"foolsday" => array(111)
);
echo "todo: load \$userstatuses to table userstatus <br>";

echo "todo: create table useraccesses: id | user_id | access <br>";
echo "todo: insert accesses to useraccesses from userstatuses <br>";

?>
