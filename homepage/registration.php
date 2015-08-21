<?php
$name       = @trim(stripslashes($_POST['name'])); 
$email       = @trim(stripslashes($_POST['email'])); 
$type    = @trim(stripslashes($_POST['type'])); 
$phone    = @trim(stripslashes($_POST['phone'])); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "knovid";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * From user_registration where email like '" . $email . "'";

$result = $conn->query($sql);

if ($result->num_rows > 0)
{
	echo 1;
	die;
}

$sql = "INSERT INTO user_registration (type, name, email, phone)
VALUES ('" . $type . "', '" . $name . "','" . $email . "','" . $phone . "')";

if ($conn->query($sql) === TRUE) {
    echo "0";
	

	/*$subject    = "New User Registered!!!"; 
	$message    = "User: {$name} registered successfully in KnoVid.com";
	$to   		= 'prabudiss@gmail.com';//replace with your email

	$headers   = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/plain; charset=iso-8859-1";
	$headers[] = "From: {$name} <{$email}>";
	$headers[] = "Reply-To: <{$email}>";
	$headers[] = "Subject: {$subject}";
	$headers[] = "X-Mailer: PHP/".phpversion();
	
	$headers1 = implode("\r\n", $headers);

	mail($to, $subject, $message, $headers1);*/
	
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>