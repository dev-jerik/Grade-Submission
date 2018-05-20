<?php
    include "../Model/Helper.php";
    class CommonModel extends Helper{
        public function __construct($DB_CON){
            parent::__construct($DB_CON);
        }

        public function getCurrentSem(){
            $date = Date('n');
            //second sem range of month
            if ($date >= 1 &&  $date <= 5){
                return 2;
            }//1st sem randge of month
            else if ($date >= 8  && $date <= 12){
                return 1;
            }else{
                return 3; //summer
            }
        }
        
        public function showDepartment(){
            $sql = "SELECT * FROM department";
            $data = $this->getData($sql);
            
            print_r($data);
        }

        public function generateYear($year=null){
            if($year==null){
                $year=Date('Y');
            }
            $endYear = Date('Y');
            $result = "";
            for($startYear=2010;$startYear <= $endYear; $startYear++){
               $selected = "";
               if ($startYear == $year) {
                    $selected="selected";
               }
               $result .= "<option value=".$startYear." ".$selected.">".$startYear."</option>";     
            }
            return $result;
        }
    }
?>