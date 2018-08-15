<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 03/08/2018
 * Time: 08:07
 */

namespace App\Service;

use App\Entity\Trick;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TrickService
{
    private $doctrine;
    private $security;
    private $validator;
    private $errorStringMessage;
    private $videoService;
    private $pictureService;
    /**
     * @var ConstraintViolationListInterface
     */
    private $error;

    /**
     * TrickService constructor.
     * @param RegistryInterface $doctrine
     * @param Security $security
     */
    public function __construct(
        RegistryInterface $doctrine,
        Security $security,
        ValidatorInterface $validator,
        PictureService $pictureService,
        VideoService $videoService
    ) {
        $this->doctrine  = $doctrine;
        $this->security  = $security;
        $this->validator = $validator;
        $this->pictureService = $pictureService;
        $this->videoService = $videoService;
    }

    /**
     * @param Form $formTrick
     * @return Trick
     */
    public function add(FormInterface $formTrick):Trick
    {
        $form = $formTrick->getData();

        $trick = (new Trick())
            ->setName($form['name'])
            ->setDescription($form['description'])
            ->setUser($this->security->getUser())
            ->setFamily($form['family']);

        return $trick;
    }

    /**
     * @param Trick $trick
     * @return bool
     */
    public function isValid(Trick $trick):bool
    {
        $errors = $this->validator->validate($trick);

        if ($errors->count() > 0) {
            $this->setErrorMessage($errors);

            return false;
        }
        $manager = $this->doctrine->getManager();
        $manager->persist($trick);
        $manager->flush();

        return true;
    }

    /**
     * @param FormInterface $form
     * @param Trick $trick
     * @return bool
     */
    public function trickHasPicture(FormInterface $form, Trick $trick)
    {
        if ($form->get('image')->getData()) {
            $this->pictureService->savePicture($form->get('image')->getData(), $trick);
        }

        return false;
    }

    public function trickHasVideo(FormInterface $formTrick, Trick $trick)
    {
        if ($formTrick->get('image')->getData()) {
            $this->videoService->saveVideo($formTrick->get('videos')->getData(), $trick);
        }

        return false;
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return void
     */
    private function setErrorMessage(ConstraintViolationListInterface $errors)
    {
        $stringError = "";
        for ($i = 0; $i<$errors->count(); $i++) {
            $stringError .= " ".$errors->get($i)->getMessage();
        }

        $this->errorStringMessage = $stringError;
        $this->error = $errors;
    }

    /**
     * @return string
     */
    public function getStringErrorsMessage():string
    {
        return $this->errorStringMessage;
    }

    /**
     * @return array
     */
    public function getErrorsByField():array
    {
        $errors = $this->error;
        $error = [];
        for ($i = 0; $i<$errors->count(); $i++) {
            $property = $errors->get($i)->getPropertyPath();
            $message = $errors->get($i)->getMessage();
            $error[$property] = $message;
        }

        return $error;
    }
}
