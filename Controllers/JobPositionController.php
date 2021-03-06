<?php
    namespace Controllers;

    use Models\JobPosition as JobPosition;
    use Models\Alert as Alert;
    use Exception as Exception;
    use DAO\JobPositionDAO as JobPositionDAO;

    class JobPositionController{
        private $jobPositionDAO;
        
        public function __construct()
        {
            $this->jobPositionDAO = new JobPositionDAO();
        }

        public function GetAll(){
            $jobPositionList = $this->jobPositionDAO->getAll();
            return $jobPositionList;
        }

        public function GetOne($id){
            $jobPosition = new JobPosition();
            $jobPosition = $this->jobPositionDAO->getOne($id);

            return $jobPosition;
        }

        public function ShowListView(){

            $jobPositionList = $this->GetAll();

            require_once(VIEWS_PATH."JobPositionView.php");
        }

        public function UpdateDatabase($APIData){
            $this->jobPositionDAO->updateDatabase($APIData);
        }

        /*
        public function ValidateJobPositionsAgainstAPI($APIData){
            try{
                $this->jobPositionDAO->validateJobPositionsAgainstAPI($APIData);
            }catch(Exception $ex)
            {
                throw $ex;
            }
        }
        */

        /*
        //Funcion para recibir datos desde a la API y guardarlos en una BD;
        public function RecieveFromAPI($APIData){
            $this->jobPositionDAO->recieveFromAPI($APIData);
        }
        */
    }
?>