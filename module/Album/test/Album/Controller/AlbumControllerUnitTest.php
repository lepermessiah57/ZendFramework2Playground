<?php

namespace Album\Controller;

use \Phake;
use Album\Form\AlbumForm;
use Zend\Stdlib\Parameters;
use Zend\Http\Request;
class AlbumControllerUnitTest extends \PHPUnit_Framework_TestCase {

	private $controller;
	private $album_table;
	private $sm;

	public function setUp(){
		$this->sm = Phake::mock('Zend\ServiceManager\ServiceManager');
		$this->album_table = Phake::mock('Album\Model\AlbumTable');
		Phake::when($this->sm)->get('Album\Model\AlbumTable')->thenReturn($this->album_table);

		$this->controller = new AlbumController();
		$this->controller->setServiceLocator($this->sm);
	}

	public function testIndexActionReturnsUsAViewModel(){
		$actual = $this->controller->indexAction();

		$this->assertTrue($actual instanceof \Zend\View\Model\ViewModel);
	}

	public function testIndexActionReturnsUsAViewModelWithCorrectContents(){
		Phake::when($this->album_table)->fetchAll()->thenReturn('DATA');

		$actual = $this->controller->indexAction();
		$expected = new \Zend\View\Model\ViewModel(array('albums'=>'DATA'));

		$this->assertEquals($expected, $actual);
	}

	public function testAddActionGetRequest(){
		$request = new \Zend\Http\Request();
		$this->setRequest($request);

		$actual = $this->controller->addAction();

		$form = new AlbumForm();
		$form->get('submit')->setValue('Add');
		$expected = array('form'=>$form);

		$this->assertEquals($expected, $actual);
	}

	public function testAddActionPostRequestInvalidForm(){
		$request = new \Zend\Http\Request();
		$request->setMethod('POST');

		$this->setRequest($request);

		$actual = $this->controller->addAction();

		$this->assertEquals(2, count($actual['form']->getMessages()));
	}

	public function testAddActionPostRequestValidFormCreatesANewAlbum(){
		$request = new \Zend\Http\Request();
		$request->setMethod('POST');
		$parameters = new Parameters();
		$parameters->set('artist', 'Lamb of God');
		$parameters->set('title', 'Black Label');
		$parameters->set('id', '1');
		$request->setPost($parameters);

		$pm = Phake::mock('Zend\Mvc\Controller\PluginManager');
		$this->controller->setPluginManager($pm);

		$this->setRequest($request);		
		$actual = $this->controller->addAction();

		$form = new AlbumForm();
		$form->get('submit')->setValue('Add');
		$expected = array('form'=>$form);

		$this->assertEquals($expected, $actual);
	}

	private function setRequest($request){
		$prop = new \ReflectionProperty($this->controller, 'request');
        $prop->setAccessible(true);
        $prop->setValue($this->controller, $request);
	}
}