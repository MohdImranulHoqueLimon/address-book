<?php

namespace App\Controller;

use App\Entity\Address;
use App\Kernel;
use App\Service\AddressService;
use App\Service\FileService;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File;

class AddressController extends Controller
{

    private $fileService;
    private $addressService;

    public function __construct(FileService $fileService, AddressService $addressService)
    {
        $this->fileService = $fileService;
        $this->addressService = $addressService;
    }

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
     * @Route("/save", name="save_address", methods={"POST"})
     */
    public function save(Request $request)
    {
        $uploadLocation = $this->getParameter('image_directory');
        $this->addressService->saveAddress($request, $uploadLocation);

        $this->addFlash(
            'success',
            'Successfully saved data.'
        );

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
        $uploadLocation = $this->getParameter('image_directory');
        $this->addressService->updateAddress($request, $uploadLocation);
        return $this->redirect('list');
    }

    /**
     * @Route("/delete/{id}", name="delete_address")
     */
    public function delete($id)
    {
        $address = $this->getDoctrine()->getRepository(Address::class)->find($id);
        $uploadLocation = $this->getParameter('image_directory') . $address->getImage();

        if (empty($address)) {
            $this->addFlash('error', 'Address not found');
            return $this->redirectToRoute('list');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        $this->fileService->removeFile($uploadLocation);
        $this->addFlash('success', 'Address Removed');

        return $this->redirect('/list');
    }
}
