<?php

    /*var_dump ($_FILES['file']['name']);*/
     
//echo $_FILES['file']['name'];
//exit;
  $rand_name=time();
  $final_name=$rand_name.$_FILES['file']['name'];

  /*echo $final_name;
  exit;*/

    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $final_name);
        echo $final_name;
    }

?>