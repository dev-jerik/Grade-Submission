<?php
    class Helper{
        private $db = null;
        public function __construct($DB_CON){
            $this->db = $DB_CON;
        }

        public  function  executeQuery($sql, $param = null, $type="fetch"){
            try{
                $stmt = $this->db->prepare($sql);

                if($param != null){
                    $stmt->execute($param);
                }else{
                    $stmt->execute();
                }

                if($type === "fetch"){
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }else if($type === "fetchAll"){
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }catch(PDOException $ex){
                //echo $ex;
                return 0;
            }
        }

    }
?>