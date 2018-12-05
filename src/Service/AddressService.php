<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 12/4/2018
 * Time: 7:25 PM
 */

namespace App\Service;

use App\Entity\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Tests\Controller;
use Doctrine\ORM\EntityManagerInterface;

class AddressService
{
    private $fileService;
    private $entityManager;

    public function __construct(FileService $fileService, EntityManagerInterface $entityManager)
    {
        $this->fileService = $fileService;
        $this->entityManager = $entityManager;
    }

    public function saveAddress(Request $request, $uploadLocation) {

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

        if(isset($_FILES['image'])) {
            $imagePath = $this->fileService->uploadImage($uploadLocation);
            if($imagePath != '') {
                $address->setImage($imagePath);
            }
        }

        $this->entityManager->persist($address);
        $this->entityManager->flush();
    }

    public function updateAddress(Request $request, $uploadLocation) {

        $id = $request->get('id');
        $address = $this->entityManager->getRepository(Address::class)->find($id);
        $prevImagePath = $address->getImage();

        $address->setFirstName($request->get('firstName'));
        $address->setLastName($request->get('lastName'));
        $address->setBirthDay($request->get('birthDay'));
        $address->setCity($request->get('city'));
        $address->setCountry($request->get('country'));
        $address->setEmail($request->get('email'));
        $address->setPhoneNumber($request->get('phoneNumber'));
        $address->setStreetNumber($request->get('streetNumber'));
        $address->setZip($request->get('zip'));

        if(isset($_FILES['image'])) {
            $imagePath = $this->fileService->uploadImage($uploadLocation);
            if($imagePath != '') {
                $address->setImage($imagePath);
                $this->fileService->removeFile($uploadLocation . $prevImagePath);
            }
        }

        $this->entityManager->persist($address);
        $this->entityManager->flush();
    }

    public function checkIsEmailExist($email, $id) {
        $address = $this->entityManager
            ->getRepository(Address::class)
            ->findAll();
        return $address;
    }
}