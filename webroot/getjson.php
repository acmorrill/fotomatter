<?php
/*$fh = fopen("http://adam.lunarnexus.us/json.php", 'rb');
$return = '';
while (!feof($fh)) {
	$return = fread($fh, 1000);
}
print_r(json_decode($return)); */

/*print_r(json_decode(file_get_contents("http://adam.lunarnexus.us/json.php"))); */

/*$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://adam.lunarnexus.us/json.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
print_r($result); */



//make the connectionst
string query = "select * from test";
SqlConnection my_connect = new SqlConnection(connect_string);
SqlCommand my_command = new SqlCommand(query, my_connect);

my_connect.open();
SqlDataReader reader;
reader = my_command.ExecuteReader();

while (
