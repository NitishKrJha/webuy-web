<div class="content">

<?php  
         foreach ($recordset as $singleRecord)  
         {  
            //$deleteLink = str_replace('{{ID}}', $row['id'], $delete_link);
            ?><div class="x"> 
           <h2><?php echo $singleRecord['first_name']; ?></h2>
            <h2><?php echo $singleRecord['last_name']; ?></h2>
             <h2><?php echo $singleRecord['email']; ?></h2>
             
            </div> 
         <?php }  
         ?>  
         </div>