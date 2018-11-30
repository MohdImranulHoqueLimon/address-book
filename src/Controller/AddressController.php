<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends Controller
{
    /**
     * @Route("/limon", name="limon")
     */
    public function index()
    {
        return new JsonResponse(array('result' => 'this is test result' ));
    }

    /**
     * @Route("/dhaka", name="dhaka")
     */
    public function dhaka()
    {
        return new JsonResponse(array('result' => 'this is dhaka' ));
    }
}
