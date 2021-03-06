<?php
    namespace DAO;

    use \Exception as Exception;
    use Models\Career as Career;
    
    class CareerDAO implements IDAO
    {
        private $connection;
        private $tableName = "careers";

        public function add($career){
            try
            {
                $query = "INSERT INTO ".$this->tableName." (description, active) VALUES (:description, :active);";
                

                $parameters['description'] = $career->getDescription();
                $parameters['active'] = $career->getActive();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        //Modificar una linea buscando el objetivo por [description]
        public function modifyByDescription($modifiedCareer){
            try
            {
                $query = "UPDATE ".$this->tableName." SET active = :active WHERE description=:description;";

                $parameters['description'] = $modifiedCareer->getDescription();
                $parameters['active'] = $modifiedCareer->getActive();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        //Funcion para actualizar la base de datos comparando con la API
        public function updateDatabase($APIData){
            try{
                foreach($APIData as $row){
                    $APICareer = new Career();
                    $APICareer->setDescription($row['description']);

                    if($row["active"]){
                        $APICareer->setActive(1);
                    }else{
                        $APICareer->setActive(0);
                    }

                    $DBCareer = $this->getOneByDescription($APICareer->getDescription());

                    if($DBCareer->getDescription() != null && strcmp($DBCareer->toString(),$APICareer->toString()) != 0){
                        $APICareer->setCareerId($DBCareer->getCareerId());
                        $this->modifyByDescription($APICareer);
                    }elseif($DBCareer->getDescription() == null){
                        $this->add($APICareer);
                    }
                }
            }catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getOneByDescription($description){
            try{

                $query = "SELECT * FROM ".$this->tableName." WHERE description=:description;";

                $parameters['description'] = $description;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                   
                $career = new Career();

                if($resultSet != null){
                    $career->setCareerId($resultSet[0]["careerId"]);
                    $career->setDescription($resultSet[0]["description"]);
                    $career->setActive($resultSet[0]["active"]);
                }

                return $career;
            }catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function filterByDescription($description){
            try{
                $careerList = array();

                $query = "SELECT * FROM ".$this->tableName." WHERE description LIKE :description;";

                $parameters['description'] = '%'.$description.'%';

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);

                foreach ($resultSet as $row)
                {                
                    $career = new Career();

                    $career->setCareerId($row["careerId"]);
                    $career->setDescription($row["description"]);
                    $career->setActive($row["active"]);

                    array_push($studentList, $career);
                }

                return $careerList;
            }catch(Exception $ex)
            {
                throw $ex;
            }
        }
        
        public function getAll(){
            try
            {
                $careerList = array();

                $query = "SELECT * FROM ".$this->tableName.";";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);

                foreach ($resultSet as $row)
                {                
                    $career = new Career();

                    $career->setCareerId($row["careerId"]);
                    $career->setDescription($row['description']);
                    $career->setActive($row["active"]);
                    
                    array_push($careerList, $career);
                }

                return $careerList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function remove($id){
            try
            {
                $query = "DELETE FROM ".$this->tableName." WHERE careerId=:careerId;";

                $parameters['careerId'] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getOne($id){
            try
            {
                $query = "SELECT * FROM ".$this->tableName." WHERE careerId=:careerId;";

                $parameters['careerId'] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);

                $career = new Career();

                if($resultSet != null){    
                    $career->setCareerId($resultSet[0]["careerId"]);
                    $career->setDescription($resultSet[0]["description"]);
                    $career->setActive($resultSet[0]["active"]);
                }

                return $career;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function modify($modifiedCareer){
            try
            {
                $query = "UPDATE ".$this->tableName." SET description = :description, active = :active WHERE careerId=:careerId;";

                $parameters['careerId'] = $modifiedCareer->getCareerId();
                $parameters['description'] = $modifiedCareer->getDescription();
                $parameters['active'] = $modifiedCareer->getActive();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        /*
        public function recieveFromAPI($APIData){       
            try{

                foreach($APIData as $row){
                    $career = new Career();

                    $career->setDescription($row["description"]);
                    $career->setActive($row["active"]);

                    $this->add($career);
                }

            }catch(Exception $ex){
                throw $ex;
            }
        }
        */
    }
?>