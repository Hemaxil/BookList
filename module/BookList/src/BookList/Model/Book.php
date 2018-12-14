<?php

namespace BookList\Model;
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;



 class Book implements InputFilterAwareInterface{
	public $id;
	public $title;
	protected $inputFilter;

     public function getArrayCopy()
     {
         return get_object_vars($this);
     }

     public function exchangeArray($data){

		$this->id=(!empty($data['id']))?$data['id']:null;
		$this->title=(!empty($data['title']))?$data['title']:null;
	}

	public function setInputFilter(InputFilterInterface $inputFilter){
		throw new \Exception("Not used");

	}

	public function getInputFilter(){
		if(!$this->inputFilter){
			$inputFilter=new InputFilter();
//            $inputFilter->add(array(
//                'name'     => 'id',
//                'required' => true,
//                'filters'  => array(
//                    array('name' => 'Int'),
//                ),
//            ));


            $inputFilter->add(array(
			    'name'=>'title',
                'required'=>true,
                'filters'=>array(
                    array('name'=>'StringTrim',
                          'name'=>'StripTags',),
                ),
                'validators'=>array(
                    array(
                        'name'=>'StringLength',
                        'options'=>array(
                            'encoding' => 'UTF-8',
                            'min'=>1,
                            'max'=>10,

                        ),
                    ),


                )
            ));
            $this->inputFilter = $inputFilter;
		}
        return $this->inputFilter;
	}
}