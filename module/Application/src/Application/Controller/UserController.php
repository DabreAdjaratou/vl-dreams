<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

class UserController extends AbstractActionController{
    public function indexAction(){
        $request = $this->getRequest();
        if ($request->isPost()){
            $parameters = $request->getPost();
            $userService = $this->getServiceLocator()->get('UserService');
            $result = $userService->getAllUsers($parameters);
            return $this->getResponse()->setContent(Json::encode($result));
        }else{
            $roleCode=base64_decode($this->params()->fromRoute('role'));
            $countryService = $this->getServiceLocator()->get('CountryService');
            $countryList=$countryService->getActiveCountries('employee');
            return new ViewModel(array(
                'countries'=>$countryList,
                'roleCode'=>$roleCode
            ));
        }
    }
    
    public function addAction(){
        $userService = $this->getServiceLocator()->get('UserService');
        if($this->getRequest()->isPost()){
            $params=$this->getRequest()->getPost();
            $result=$userService->addUser($params);
            return $this->_redirect()->toRoute('user');
        }else{
            $roleService = $this->getServiceLocator()->get('RoleService');
            $countryService = $this->getServiceLocator()->get('CountryService');
            $result=$roleService->getActiveRoles();
            $countryList=$countryService->getActiveCountries('employee');
            return new ViewModel(array(
            'roleData'=>$result,
            'countries'=>$countryList
            ));
        }
    }
    
    public function editAction(){
        $userService=$this->getServiceLocator()->get('UserService');
         if($this->getRequest()->isPost()){
            $params=$this->getRequest()->getPost();
            $userService->updateEmployee($params);
            return $this->redirect()->toRoute('employee');
        }else{
            $userId=base64_decode($this->params()->fromRoute('id'));
            $result=$userService->getUser($userId);
            $roleService = $this->getServiceLocator()->get('RoleService');
            $countryService = $this->getServiceLocator()->get('CountryService');
            $roleResult=$roleService->getActiveRoles();
            $countryList=$countryService->getActiveCountries('employee');
            return new ViewModel(array(
                'empResult'=>$result,
                'countries'=>$countryList,
                'roleData'=>$roleResult
            ));
        }
    }
  
}
