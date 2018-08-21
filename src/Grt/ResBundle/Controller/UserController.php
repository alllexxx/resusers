<?php

namespace Grt\ResBundle\Controller;

use Grt\ResBundle\Entity\Resource;
use Grt\ResBundle\GrtResBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\HttpFoundation\Request;
use Grt\ResBundle\Entity\User;
use Grt\ResBundle\Entity\Base;
use Grt\ResBundle\Form\UserType;
use Grt\ResBundle\Form\ResourceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\File\Upload;
/**
 * Class UserController
 * @package Intex\OrgBundle\Controller
 */
class UserController extends Controller
{

    /**
     * Render list all users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUsersAction($page = 1, $field = 'lastname', $order = 'ASC')
    {
        $em = $this->getDoctrine()
            ->getManager();

        $users = $em->getRepository('GrtResBundle:User')
            ->getAllUsers($field, $order, $page, $this->container->getParameter('per_page'));

        $maxPages = ceil($users->count() / $this->container->getParameter('per_page'));
        $thisPage = $page;

        return $this->render('GrtResBundle:User:index.html.twig', array(
            'users' => $users,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
        ));

    }

    public function showLdapUsersAction()
    {

        $form = $this->createFormBuilder()
            ->add('location', EntityType::class, array(
                'class'      => 'Grt\ResBundle\Entity\Location',
                'choice_label' => 'name',
                'label' => 'Выберите ПТО: '
            ))
            ->add('department', EntityType::class, array(
                'class'      => 'Grt\ResBundle\Entity\Department',
                'choice_label' => 'name',
                'label' => 'Выберите Отдел: ',
                'placeholder' => '',
                'required' => false,
                'empty_data' => 0,
                'data' => 0,
                'choice_value' => 'id'
            ))

            ->getForm()->createView();

        return $this->render('GrtResBundle:User:ldap-choice.html.twig', array(
            'form' => $form
        ));

    }

    public function deactivateUserAction($userId, $activity)
    {
        try{

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('GrtResBundle:User')->find($userId);

            if (!$user) {
                throw $this->createNotFoundException('Unable to find user.');
            }

            if (!in_array($activity, array('true','false'))){
                throw $this->createNotFoundException('Unable to activate user.');
            }

            $active = $activity === 'true'? true: false;

            $user->setActive($active);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('User deactivated success'));
            return $this->redirect($this->generateUrl('grt_users'));
        } catch (Exception $e){
            $this->addFlash('error', $this->get('translator')->trans('Unnable deactivate user'));
            return $this->redirect($this->generateUrl('grt_users'));
        }
    }



    public function searchFormAction()
    {
        return $this->render('GrtResBundle:User:search.html.twig', array(
            'form' => $this->createSearchForm()
        ));
    }

    private function createSearchForm()
    {
        $form = $this->createFormBuilder()
            ->add('location', EntityType::class, array(
                'class'      => 'Grt\ResBundle\Entity\Location',
                'choice_label' => 'name',
                'label' => 'Выберите ПТО: ',
                'attr' => array(
                    'placeholder' => 'Enter product name'
                ),
                'required' => false
            ))
            ->add('department', EntityType::class, array(
                'class'      => 'Grt\ResBundle\Entity\Department',
                'choice_label' => 'name',
                'label' => 'Выберите Отдел: ',
                'attr' => array(
                    'placeholder' => 'Enter product name'
                ),
                'required' => false
            ))
            ->add('lastname',TextType::class, array(
                    'label' => 'Фамилия:',
                    'attr'=> array('class'=>'form-control'),
                    'required' => false
                )
            )
            ->getForm()->createView();
        return $form;
    }

    public function searchUserAction(Request $request)
    {
        $form = $request->get("form");

        if (isset($form['location']) && $form['location']!=''){
            $location = $this->getLocationById(intval($form['location']));
        } else {
            $location = null;
        }

        if (isset($form['department']) && $form['department']!=''){
            $department = $this->getDepartmentById(intval($form['department']));
        } else {
            $department = null;
        }

        if(isset($form['lastname']) && $form['lastname']!=''){
            $lastname = $form['lastname'];
        } else {
            $lastname = null;
        }

        $em = $this->getDoctrine()
            ->getManager();
        if (!($location||$department||$lastname)){
            $this->addFlash('warning', $this->get('translator')->trans('Заполните хотя бы одно поле'));
            return $this->redirect($this->generateUrl('grt_search_user'));
        }
        $users = $em->getRepository('GrtResBundle:User')
            ->findUsers($lastname, $location, $department);

        $maxPages = 1;

        if ($users) {

            return $this->render('GrtResBundle:User:search.html.twig', array(
                'users' => $users,
                'maxPages' => $maxPages,
                'form' => $this->createSearchForm()
            ));
        } else {
            $this->addFlash('warning', $this->get('translator')->trans('Поиск не дал результата'));
        }

        return $this->redirect($this->generateUrl('grt_search_user'));
    }

    public function listLdapUsersAction(Request $request)
    {
        try{
            $form = $request->get("form");
            $loc =$form['location'];
            $location = $this->getLocationById(intval($form['location']));


            $ldap = Ldap::create('ext_ldap', array(
                'host' => '10.16.3.2'
            ));


            $ldap_name = $this->container->getParameter('ldap_name');
            $ldap_pswd = $this->container->getParameter('ldap_password');
            $ldap->bind($ldap_name, $ldap_pswd);

            if ($location->getKod()==0){
                $kod = '000';
            } else {
                $kod = $location->getKod();
            }

            $dn = 'OU=Users';
            if ($form['department']!=''){
                $department = $this->getDepartmentById(intval($form['department']));
                $dn = $dn.',OU='.$department->getName();
            } else {
                $department['id'] = 0;
            }


            $dn = $dn.',OU='.$kod.',DC=grt,DC=customs,DC=gov,DC=by';

            $query = $ldap->query($dn, '(objectclass=*)'); //sAMAccountName=ChmakAV
            $ldapUsers = $query->execute()->toArray();
            // Pass through the 3 above variables to calculate pages in twig
            return $this->render('GrtResBundle:User:ldap-users.html.twig', array(
                'ldapUsers' => $ldapUsers,
                'location' => $location,
                'department' => $department
            ));
        } catch (Exception $e) {
            $this->addFlash('error', $this->get('translator')->trans('User can not be load'));
            return $this->redirect($this->generateUrl('grt_ldap_choice'));
        }
    }

    public function uploadLdapUsersAction(Request $request,$locationId, $departmentId = null)
    {
        try {
            $location = $this->getLocationById($locationId);

            $users = $request->get("users");
            $ldap = Ldap::create('ext_ldap', array(
                'host' => '10.16.3.2'
            ));

            $ldap_name = $this->container->getParameter('ldap_name');
            $ldap_pswd = $this->container->getParameter('ldap_password');
            $ldap->bind($ldap_name, $ldap_pswd);

            if ($location->getKod()==0){
                $kod = '000';
            } else {
                $kod = $location->getKod();
            }

            $dn = 'OU=Users';
            if ($departmentId){
                $department = $this->getDepartmentById($departmentId);
                $dn = $dn.',OU='.$department->getName();
            }

            $dn = $dn.',OU='.$kod.',DC=grt,DC=customs,DC=gov,DC=by';

            $query = $ldap->query($dn, '(objectclass=*)'); //sAMAccountName=ChmakAV
            $ldapUsers = $query->execute()->toArray();

            $em = $this->getDoctrine()->getManager();
            $counter = 0;
            foreach ($users as $user){
                foreach ($ldapUsers as $ldapUser){
                    if (($user==$ldapUser->getAttribute('sAMAccountName')[0])&&(!$em->getRepository('GrtResBundle:User')->findBy(array('domainname'=>$ldapUser->getAttribute('sAMAccountName')[0])))){
                        $worker = new User();
                        $worker->setLocation($location);
                        $worker->setDepartment($department);
                        $worker->setDomainname($ldapUser->getAttribute('sAMAccountName')[0]);
                        $cn = $ldapUser->getAttribute('cn');
                        $name = explode(' ', $cn[0]);
                        $worker->setLastname($ldapUser->getAttribute('givenName')[0]);
                        $worker->setFirstname($name[1]);
                        $worker->setMiddlename($name[2]);
                        $em->persist($worker);
                        $em->flush();
                        $counter++;
                    }
                }
            }
            $this->addFlash('success', $this->get('translator')->trans($counter. ' users successfully loaded'));
            return $this->redirect($this->generateUrl('grt_ldap_choice'));
        } catch (Exception $e) {
            $this->addFlash('error', $this->get('translator')->trans('User can not be load'));
            return $this->redirect($this->generateUrl('grt_ldap_choice'));
        }
    }

    public function editUsersAction($userId)
    {
        $em = $this->getDoctrine()
            ->getManager();
        $user = $em->getRepository('GrtResBundle:User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        $form = $this->createForm(UserType::class, $user);

        return $this->render('GrtResBundle:User:form.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));

    }


    public function deleteUserAction($userId)
    {
        try{
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('GrtResBundle:User')->find($userId);

            if (!$user) {
                throw $this->createNotFoundException('Unable to find user.');
            }

            $em->remove($user);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('User deleted success'));
            return $this->redirect($this->generateUrl('grt_users'));
        } catch (Exception $e){
            $this->addFlash('error', $this->get('translator')->trans('Unnable delete user'));
            return $this->redirect($this->generateUrl('grt_users'));
        }
    }
    /**
     * Render information about user by id
     * @param int $userId Id user's
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('GrtResBundle:User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        $resources = $user->getResources();
        $form = $this->createFormBuilder()
            ->add('base', EntityType::class, array(
                'class'      => 'Grt\ResBundle\Entity\Base',
                'choice_label' => 'name',
                'label' => 'Выберите ресурс: '
             ))->getForm()->createView();

        return $this->render('GrtResBundle:User:show.html.twig', array(
            'user' => $user,
            'resources' => $resources,
            'form' => $form
        ));
    }

    /**
     * Renders list users of the company
     * @param int $companyId Id organization's
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listOrgUsersAction($companyId, $page = 1, $field = 'lastname', $order = 'ASC')
    {
        $company = $this->getCompany($companyId);
        $users = $company->getUsers();

        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        $usersSort = $users->toArray();
        $this->get('app.sort_array')->sortArrayByKey($usersSort, $field, $order);


        $maxPages = ceil($users->count() /$this->container->getParameter('per_page'));
        $thisPage = $page;

        return $this->render('GrtResBundle:User:users.html.twig', array(
            'company' => $company,
            'users' =>   $usersSort,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
        ));
    }

    /**
     * Renders form for add user to company
     * @param int $companyId organization's Id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newUserAction()
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        return $this->render('GrtResBundle:User:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Add user in DB
     * @param Request $request
     * @param int $userId organization's Id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createUserAction(Request $request, $userId = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($userId) {
            $user = $em->getRepository('GrtResBundle:User')->find($userId);
        } else {
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('User was be added!'));
            return $this->redirect($this->generateUrl('grt_users'));
        }

        $this->addFlash('error', $this->get('translator')->trans('User can not be added'));
        return $this->render('GrtResBundle:User:form.html.twig', array(
            'form' => $form->createView()
        ));
    }



    /**
     * Add resource to user
     * @param Request $request
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addResourceUserAction(Request $request, $userId)
    {
        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $admin= $this->container->get('security.token_storage')->getToken()->getUser();

        }

        $em = $this->getDoctrine()->getManager();
        $form = $request->get("form");
        $base = $this->getBaseById(intval($form['base']));
        $resource = new Resource();
        $baseId = strval($form['base']);
        $user = $em->getRepository('GrtResBundle:User')->find($userId);

        if ($em->getRepository('GrtResBundle:Resource')->getFindUserBase($baseId,$userId)){
            $this->addFlash('warning', $this->get('translator')->trans('Base already exist!'));
            return $this->redirect($this->generateUrl('grt_user_show',array('userId' => $userId)));
        }

        $formRes = $this->createFormBuilder($resource);

        $fields = explode(",", $base->getFields());
            foreach ($fields as $field){
                if ($field == 'term'){
                    $registered = date('Y-m-d');
                    $formRes->add('term', DateType::class, array('label' => 'Срок действия(DD-MM-YYYY)',
                        'widget' => 'single_text','input' => 'string','format' => 'dd-MM-yyyy','data'=>$registered,'attr'=> array('class'=>'input-group date form-control')));
                } elseif ($field == 'flag') {
                    $formRes->add('flag',ChoiceType::class, array( 'label'=>'Установлен в НАСЭД',
                        'choices' => array(
                            'Нет' => '0',
                            'Да' => '1',
                        )
                    ));
                } elseif ($field == 'docFileName') {
                    $formRes->add('docFile', VichImageType::class, array('label' => 'Прикрепить документ',
                         'required' => false,
                         ));
                } else {
                    $formRes->add($field,TextType::class, array('label' => $field,'attr'=> array('class'=>'form-control')));
                }
            }

        $formRes->add('admin',EntityType::class, array(
            'class'      => 'Grt\ResBundle\Entity\Admin',
            'required' => true,
            'label'=>'',
            'choice_label' => 'id',
            'data' => $admin,
            'empty_data' => $admin,
            'attr'=>array('style'=>'display:none;')
            )
        );

        $f = $formRes->getForm()->createView();
        return $this->render('GrtResBundle:Resource:form.html.twig', array(
            'form' => $formRes->getForm()->createView(),
            'user' => $user,
            'baseId' => $baseId,

        ));
    }



    public function createUserResourceAction(Request $request, $userId, $baseId, $resourceId=null)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUserById($userId);
            $base = $this->getBaseById($baseId);


            if ($resourceId) {
                $resource = $em->getRepository('GrtResBundle:Resource')->find($resourceId);
            } else {
                $resource = new Resource();
                $resource->setUser($user);
                $resource->setBase($base);

            }

            if ((!$user) || (!$base)) {
                throw $this->createNotFoundException('Unable to find user or base.');
            }


            $fields = explode(",", $base->getFields());
            $form = $request->get("form");


            $admin = $em->getRepository('GrtResBundle:Admin')->find($form['admin']);
            if (!$admin){
                throw $this->createNotFoundException('Unable to find Admin.');
            }

            $resource->setAdmin($admin);

            $uploadedFile = $request->files->get('form');
            if (null !== $uploadedFile){
                $resource->setDocFile($uploadedFile['docFile']['file']);
                $file = $uploadedFile['docFile']['file'];
                $resource->setDocFileName($file->getPathName());
            }

            foreach ($fields as $field) {
                $resource->$field = $form[$field];
            }

            $em->persist($resource);

            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('Resource successfully added or updated!'));
            return $this->redirect($this->generateUrl('grt_user_show',array('userId' => $userId)));
        } catch (Exception $e){
            return $this->redirect($this->generateUrl('grt_users'.$e));
        }


    }


    /**
     * Renders form for upload users from XML file
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadXmlAction()
    {
        $form = $this->createFormBuilder()
            ->add('file', FileType::class, array('label' => $this->get('translator')->trans('Load XML file'),
                "attr" => array("accept" => ".xml",)))
            ->getForm();

        return $this->render('GrtResBundle:User:upload.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Shows the company in which the user belongs
     * @param int $companyId Id organization's
     * @return \Grt\ResBundle\Entity\User|null|object
     */
    protected function getUserById($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('Grt\ResBundle\Entity\User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        return $user;
    }


    /**
     * Shows the company in which the user belongs
     * @param int $companyId Id organization's
     * @return \Grt\ResBundle\Entity\Base|null|object
     */
    protected function getBaseById($baseId)
    {
        $em = $this->getDoctrine()->getManager();
        $base = $em->getRepository('Grt\ResBundle\Entity\Base')->find($baseId);

        if (!$base) {
            throw $this->createNotFoundException('Unable to find base.');
        }

        return $base;
    }

    protected function getLocationById($locationId)
    {
        $em = $this->getDoctrine()->getManager();
        $location = $em->getRepository('Grt\ResBundle\Entity\Location')->find($locationId);

        if (!$location) {
            throw $this->createNotFoundException('Unable to find location.');
        }

        return $location;
    }

    protected function getDepartmentById($departmentId)
    {
        $em = $this->getDoctrine()->getManager();
        $department = $em->getRepository('Grt\ResBundle\Entity\Department')->find($departmentId);

        if (!$department) {
            throw $this->createNotFoundException('Unable to find department.');
        }

        return $department;
    }
}
