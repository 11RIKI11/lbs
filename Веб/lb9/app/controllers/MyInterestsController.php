<?php

require_once __DIR__.'/BaseController.php';

class MyInterestsController extends BaseController {
    #[Route("myinterests", 'GET')]
    public function index(){
        $title = 'Мои интересы';
        InterestsCategoriesRecord::initializeTable();
        InterestsModel::initializeTable();
        $interestsCategories = InterestsCategoriesRecord::findAllAssoc();
        $this->view("MyInterests", ['title' => $title, 'interestsCategories' => $interestsCategories]);
    }
}

?>