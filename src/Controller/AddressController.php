<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends Controller
{
    /**
     * @Route("/list", name="list_addresses")
     */
    public function indexAction()
    {
        $address = $this->getDoctrine()
            ->getRepository(Address::class)->findAll();
        return $this->render('address/index.html.twig', array('address' => $address));
    }

    /**
     * @Route("/create", name="create_address")
     */
    public function createAction(Request $request)
    {
        return $this->render('address/create.html.twig');
    }

    /**
     * @Route("/save", name="save_address")
     */
    public function saveAction(Request $request)
    {
        $address = new Address();

        $address->firstName = $request->get('firstName');
        $address->lastName = $request->get('lastName');

        $em = $this->getDoctrine()->getManager();
        $em->persist($address);
        $em->flush();

        return $this->redirect('list');
    }
}
