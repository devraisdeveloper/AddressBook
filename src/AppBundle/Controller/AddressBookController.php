<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\AddressBook;
use Symfony\Component\HttpFoundation\Session\Session;

class AddressBookController extends Controller
{
    /**
     * @Route("/addresses", name="address_list")
     */
    public function indexAction(Request $request)
    {
        $addresses = $this->getDoctrine()->getRepository(AddressBook::class)->findAll();

    /**
     * @var $paginator \Knp\Component\Pager\Paginator
     */
        $paginator = $this->get('knp_paginator');

        $result = $paginator->paginate(
                $addresses,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 5)
        );

        // Render the template
       return $this->render('address/index.html.twig', [
        'addresses' => $result
        ]);
    }

    /**
     * @Route("/address/create", name="address_create")
     */
    public function createAction(Request $request)
    {
        $entry = new AddressBook();

        $form = $this->createFormBuilder($entry)
        ->add('name', TextType::class, ['attr' =>[
                                                    'class' => 'form-control',
                                                 ]
                                       ])
        ->add('email', TextType::class, ['attr' =>[
                                                    'class' => 'form-control',
                                                 ]
                                       ])
        ->add('phone', TextType::class, ['attr' =>[
                                                    'class' => 'form-control',
                                                 ]
                                       ])
        ->add('address', TextType::class, ['attr' =>[
                                                    'class' => 'form-control',
                                                 ]
                                       ])
        ->add('save', SubmitType::class, [
                                                'label' => 'Add Address',
                                                'attr' =>[
                                                    'label' => 'Add Address',
                                                    'class' => 'btn btn-primary',
                                                    'style' => 'margin-bottom:250x'
                                                 ]
                                         ])
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager = $this->getDoctrine()->getManager();

            $address = urlencode($entry->getAddress());

            $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

            $decodedResponse = json_decode(file_get_contents($url));

            if(empty($decodedResponse->results[0]->geometry->location->lat) || empty($decodedResponse->results[0]->geometry->location->lng)) {

                $this->addFlash('notice', 'Failed to create Geo location!');

                return $this->redirectToRoute('address_list');
            }else {

                $entry->setLatitude($decodedResponse->results[0]->geometry->location->lat);
                $entry->setLongitude($decodedResponse->results[0]->geometry->location->lng);

                $manager->persist($entry);
                $manager->flush();

            return $this->redirectToRoute('address_view', ['id' => $entry->getId()]);
            }
        }

        return $this->render('address/create.html.twig', [
        'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/address/update/{id}", name="address_update")
     */
    public function updateAction($id, Request $request)
    {
        $entry = $this->getDoctrine()->getRepository(AddressBook::class)->find($id);

        if(!$entry) throw $this->createNotFoundException('There is no address with id '.$id);

        $entry->setName($entry->getName());
        $entry->setEmail($entry->getEmail());
        $entry->setPhone($entry->getPhone());
        $entry->setAddress($entry->getAddress());

        $form = $this->createFormBuilder($entry)
        ->add('name', TextType::class, ['attr' =>[
                                                    'class' => 'form-control',
                                                 ]
                                       ])
        ->add('email', TextType::class, ['attr' =>[
                                                    'class' => 'form-control',
                                                 ]
                                       ])
        ->add('phone', TextType::class, ['attr' =>[
                                                    'class' => 'form-control',
                                                 ]
                                       ])
        ->add('address', TextType::class, ['attr' =>[
                                                    'class' => 'form-control',
                                                 ]
                                       ])
        ->add('save', SubmitType::class, [
                                                'label' => 'Edit Address',
                                                'attr' =>[
                                                    'label' => 'Add Address',
                                                    'class' => 'btn btn-primary',
                                                    'style' => 'margin-bottom:250x'
                                                 ]
                                         ])
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager = $this->getDoctrine()->getManager();

            $address = urlencode($entry->getAddress());

            $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

            $decodedResponse = json_decode(file_get_contents($url));

             if(empty($decodedResponse->results[0]->geometry->location->lat) || empty($decodedResponse->results[0]->geometry->location->lng)) {

              $this->addFlash('notice', 'Failed to update Geo location!'
              );
                return $this->redirectToRoute('address_list');

            }else {

                $entry->setLatitude($decodedResponse->results[0]->geometry->location->lat);
                $entry->setLongitude($decodedResponse->results[0]->geometry->location->lng);
                $manager->flush();

            return $this->redirectToRoute('address_view', ['id' => $entry->getId()]);
            }
        }

        return $this->render('address/create.html.twig', [
        'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/address/delete/{id}", name="address_delete")
     */
    public function deleteAction($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $address = $this->getDoctrine()->getRepository(AddressBook::class)->find($id);
        if(!$address) throw $this->createNotFoundException('There is no address with id '.$id);

        $manager->remove($address);
        $manager->flush();

      return $this->redirectToRoute('address_list');
    }

        /**
     * @Route("/address/view/{id}", name="address_view")
     */
    public function viewAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $address = $this->getDoctrine()->getRepository(AddressBook::class)->find($id);

        if(!$address) throw $this->createNotFoundException('There is no address with id '.$id);

        return $this->render('address/view.html.twig', [
        'address' => $address
        ]);
    }

}