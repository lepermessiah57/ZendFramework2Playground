<?php
namespace AlbumRest\Controller;
 
use Zend\Mvc\Controller\AbstractRestfulController;
 
use Album\Model\Album;
use Album\Form\AlbumForm;
use Album\Model\AlbumTable;
use Zend\View\Model\JsonModel;
 
class AlbumRestController extends AbstractRestfulController {
    public function getList(){
        $results = $this->getAlbumTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }
        return new \Zend\View\Model\JsonModel(array('data' => $data));
        // return array('data' => $data);
    }
 
    public function get($id){
        # code...
    }
 
    public function create($data){
        # code...
    }
 
    public function update($id, $data){
        # code...
    }
 
    public function delete($id){
        # code...
    }

    public function getAlbumTable(){
        if (!$this->albumTable) {
            $sm = $this->getServiceLocator();
            $this->albumTable = $sm->get('Album\Model\AlbumTable');
        }
        return $this->albumTable;
    }    
}