<?php

require_once __DIR__.'/BaseController.php';

class MyInterestsController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->SetRolesAccess([
            'guest' => AccessActions::Allow,
            'user' => AccessActions::Allow,
            'admin' => AccessActions::Allow,
            'superadmin' => AccessActions::Allow
        ]);
    }

    #[Route("myinterests", 'GET')]
    public function index(){
        $title = 'Мои интересы';
        if(!$this->TryGetAccess()){
            return;
        }
        InterestsCategoriesRecord::initializeTable();
        InterestsModel::initializeTable();
        $interestsCategories = InterestsCategoriesRecord::findAllAssoc();
        $this->view("MyInterests", ['title' => $title, 'interestsCategories' => $interestsCategories]);
    }
}

?>