<?php

//update_last_activity.php

 
include('../config.php');

$query = "
UPDATE login_details 
SET last_activity = now() 
WHERE login_details_id = '".$_SESSION["login_details_id"]."'
";

$statement = $conn->prepare($query);

$statement->execute();

?>
