<?php

namespace BookList\Model;
use Zend\Db\TableGateway\TableGateway;

class BookTable{
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway=$tableGateway;
	}

	public function fetchAll(){
		return $this->tableGateway->select();
	}

	public function getBook($id){
		$rowset = $this->tableGateway->select(array('id' => $id));
		$bookRow = $rowset->current();
		if(!$bookRow){
			throw new \Exception("Could not find $id") ;
		}
		return $bookRow;

	}

	public function saveBook(Book $book){

		$data=array('title'=>$book->title);
        $id=(int)$book->id;

		if($book->id==0){
			$this->tableGateway->insert($data);
		}else{

			if($this->getBook($id)) {
                $this->tableGateway->update($data, array('id'=>$id));
               /* echo "<pre>";
                print_r( $this->tableGateway->getSql());
                die;*/

            }
			else
				throw new \Exception("Could not update");
		}
	}

	public function deleteBook($id){
		$this->tableGateway->delete(array('id'=>$id));
	}

	
	
}