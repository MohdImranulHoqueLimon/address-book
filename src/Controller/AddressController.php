<?php

namespace App\Controller;

use App\Entity\Address;
use function Sodium\add;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->redirect('list');
    }

    /**
     * @Route("/list", name="list_addresses")
     */
    public function index(Request $request)
    {
        $pageNumber = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 3);


        //Todo; Should keep these kind of code inside repository
        $addresses = $this->getDoctrine()
            ->getRepository(Address::class)->findAll();

        /**
         *@var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $addresses,
            $pageNumber,
            $limit
        );

        return $this->render('address/index.html.twig', array('addresses' => $result));
    }

    /**
     * @Route("/create", name="create_address")
     */
    public function create()
    {
        return $this->render('address/create.html.twig');
    }

    /**
     * @Route("/save", name="save_address")
     */
    public function save(Request $request)
    {
        $address = new Address();

        $address->setFirstName($request->get('firstName'));
        $address->setLastName($request->get('lastName'));
        $address->setBirthDay($request->get('birthDay'));
        $address->setCity($request->get('city'));
        $address->setCountry($request->get('country'));
        $address->setEmail($request->get('email'));
        $address->setPhoneNumber($request->get('phoneNumber'));
        $address->setStreetNumber($request->get('streetNumber'));
        $address->setZip($request->get('zip'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($address);
        $em->flush();

        return $this->redirect('list');
    }

    /**
     * @Route("/edit/{id}", name="edit_address")
     */
    public function edit($id)
    {
        $address = $this->getDoctrine()->getRepository(Address::class)->find($id);
        return $this->render('address/edit.html.twig', array(
            'address' => $address
        ));
    }

    /**
     * @Route("/update", name="update_address")
     */
    public function update(Request $request)
    {
        $id = $request->get('id');
        $address = $this->getDoctrine()->getRepository(Address::class)->find($id);

        $address->setFirstName($request->get('firstName'));
        $address->setLastName($request->get('lastName'));
        $address->setBirthDay($request->get('birthDay'));
        $address->setCity($request->get('city'));
        $address->setCountry($request->get('country'));
        $address->setEmail($request->get('email'));
        $address->setPhoneNumber($request->get('phoneNumber'));
        $address->setStreetNumber($request->get('streetNumber'));
        $address->setZip($request->get('zip'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($address);
        $em->flush();

        return $this->redirect('list');
    }

    /**
     * @Route("/delete/{id}", name="delete_address")
     */
    public function delete($id)
    {
        $address = $this->getDoctrine()->getRepository(Address::class)->find($id);

        if (empty($address)) {
            $this->addFlash('error', 'Address not found');
            return $this->redirectToRoute('list');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        $this->addFlash('notice', 'Address Removed');

        return $this->redirect('/list');
    }
}
