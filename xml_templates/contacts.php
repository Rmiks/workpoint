<?php
class contacts
{

    function dynamic_output(& $module = null)
    {



        if(sizeof($_POST)){

            $contacts = new contacts();
            $result = $contacts->variablesSave($_POST);


            if($result){
                leafHttp::redirect( $_POST['returnOk'] );
            }



        }



    }


}


?>