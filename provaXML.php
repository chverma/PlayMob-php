$books = array( 
	'book' => array(
	      array('@attributes' => array('author' => 'George Orwell'),'title' => '1984'),
	      array('@attributes' => array('author' => 'Isaac Asimov'),'title' => array('@cdata'=>'Foundation'),'price' => '$15.61'),
	      array('@attributes' => array('author' => 'Robert A Heinlein'),'title' =>  array('@cdata'=>'Stranger in a Strange Land'),'price' => array('@attributes' => array('discount' => '10%'),'@value' => '$18.00')))
  );