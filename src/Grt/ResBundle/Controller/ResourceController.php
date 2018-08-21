<?php

namespace Grt\ResBundle\Controller;

use Grt\ResBundle\Form\ResourceType;
use Grt\ResBundle\Entity\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\File\Upload;
/**
 * Class BaseController
 * @package Grt\ResBundle\Controller
 */
class ResourceController extends Controller
{
    function deleteResourceAction($userId,$resourceId)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $resource = $em->getRepository('GrtResBundle:Resource')->find($resourceId);

            if (!$resource) {
                throw $this->createNotFoundException('No resource found ');
            }

            $em->remove($resource);
            $em->flush();

            $this->addFlash('success', $this->get('translator')->trans('Resource successfully deleted'));
            return $this->redirect($this->generateUrl('grt_user_show', array('userId' => $userId)));
        } catch (Exception $e){
            $this->addFlash('error', $e);
            return $this->redirect($this->generateUrl('grt_user_show', array('userId' => $userId)));
        }
    }

    function showResourceAction($userId,$resourceId)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $resource = $em->getRepository('GrtResBundle:Resource')->find($resourceId);
            $user = $em->getRepository('GrtResBundle:User')->find($userId);
            if (!$resource) {
                throw $this->createNotFoundException('No resource found ');
            }

            return $this->render('GrtResBundle:Resource:show.html.twig',array('user' => $user, 'resource' => $resource));

        } catch (Exception $e){
            $this->addFlash('error', $e);
            return $this->redirect($this->generateUrl('grt_user_show', array('userId' => $userId)));
        }
    }
    function editResourceAction($userId,$resourceId)
    {
        try {
            if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
            {
                $admin= $this->container->get('security.token_storage')->getToken()->getUser();
            }

            $em = $this->getDoctrine()->getManager();
            $resource = $em->getRepository('GrtResBundle:Resource')->find($resourceId);
            $user = $resource->getUser();
            $base = $resource->getBase();
            if (!$resource) {
                throw $this->createNotFoundException('No resource found ');
            }

            $formRes = $this->createFormBuilder($resource);

            $fields = explode(",", $base->getFields());
            foreach ($fields as $field){
                if ($field == 'term'){
                    $formRes->add('term', DateType::class, array('label' => 'Срок действия(DD-MM-YYYY)','data' => $resource->$field,
                        'widget' => 'single_text','format' => 'dd-MM-yyyy','attr'=> array('class'=>'input-group date form-control')));
                } elseif ($field == 'flag') {
                    $formRes->add('flag',ChoiceType::class, array( 'label'=>'Установлен в НАСЭД',
                        'choices' => array(
                                'Нет' => '0',
                                'Да' => '1',
                            ),
                        'data' => '0',
                        'empty_data' => '0'
                    ));
                } elseif ($field == 'docFileName') {
                    $formRes->add('docFile', VichImageType::class, array('label' => 'Прикрепить документ',
                        'required' => false,
                        'data' => $resource->getDocFile(),
                        'download_uri' => false,
                        'delete_label' => 'Delete image ?'
                    ));
                } else {
                    $formRes->add($field,TextType::class, array('label' => $field,'data'=> $resource->$field, 'attr'=> array('class'=>'form-control')));
                }
            }

            $formRes->add('admin',EntityType::class, array(
                    'class'      => 'Grt\ResBundle\Entity\Admin',
                    'required' => true,
                    'choice_label' => 'id',
                    'data' => $admin,
                    'label'=>'',
                    'empty_data' => $admin,
                    'attr'=>array('class'=>'form-control','style'=>'display:none;')
                )
            );



            return $this->render('GrtResBundle:Resource:form.html.twig', array(
                'form' => $formRes->getForm()->createView(),
                'user' => $user,
                'baseId' => $base->getId(),
                'resourceId'=>$resource->getId()
            ));


        } catch (Exception $e){
            $this->addFlash('error', $e);
            return $this->redirect($this->generateUrl('grt_user_show', array('userId' => $userId)));
        }
    }

    function checkResourcesAction()
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $resources = $em->getRepository('GrtResBundle:Resource')->getResourceTermExpired($this->container->getParameter('term_before_ending'));

            return $this->render('GrtResBundle:Resource:check.html.twig',array('resources' => $resources));

        } catch (Exception $e){
            $this->addFlash('error', $e);
            return $this->redirect($this->generateUrl('grt_show_users'));
        }
    }
}