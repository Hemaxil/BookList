<?php


namespace BookList\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use BookList\Form\BookForm;
use BookList\Model\Book;
use Zend\Debug\Debug;

class BookController extends AbstractActionController{
	protected $bookTable;
	public function indexAction(){

		return new ViewModel(array(
			'books'=>$this->getBookTable()->fetchAll(),
		));

	}
	public function addAction(){
		
		$form=new BookForm();
		$form->get('submit')->setValue('Add');
		$request=$this->getRequest();

		if($request->isPost()){
		    $book=new Book();
            $form->setInputFilter($book->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $book->exchangeArray($form->getData());
                $this->getBookTable()->saveBook($book);

                // Redirect to list of albums
                return $this->redirect()->toRoute('book');
            }


        }
	 	return array('form'=>$form);
	}
	public function editAction(){
		$id=$this->params()->fromRoute('id',0);
		
		try {
             $book = $this->getBookTable()->getBook($id);
         }
        catch (\Exception $ex) {
             return $this->redirect()->toRoute('book', array(
                 'action' => 'index'
             ));
        }

        $form=new BookForm();
		$form->bind($book);

		$form->get('submit')->setAttribute('value','Edit');
		$request=$this->getRequest();
		if($request->isPost()){
		    $form->setInputFilter($book->getInputFilter());
		    $form->setData($request->getPost());

            if($form->isValid()){
                $this->getBookTable()->saveBook($book);
                return $this->redirect()->toRoute('book');
            }

		}

		return array('form'=>$form,'id'=>$id);
		
	}
	public function deleteAction(){
		$id=(int)$this->params()->fromRoute('id',0);
		if(!$id)
			return $this->redirect()->toRoute('book',array('action'=>'index'));
		if($this->getRequest()->isPost()){
            if($this->getRequest()->getPost('del')=="Yes"){
                $this->getBookTable()->deleteBook($id);
                return $this->redirect()->toRoute('book');
            }

		}
		return array(
			'id'=>$id,
            'book'=>$this->getBookTable()->getBook($id)
		);

	}
	 public function getBookTable()
     {
         if (!$this->bookTable) {
             $sm = $this->getServiceLocator();
             $this->bookTable = $sm->get('BookList\Model\BookTable');
         }
         return $this->bookTable;
     }
}

