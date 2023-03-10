<?php

namespace App\Tests\Entity;

use App\Entity\AppsUtilisateur;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppUtilisateurTest extends KernelTestCase
{
    // private const VALID_NOM_UTILISATEUR = 'kebsibadr';

    // private const VALID_PASSWORD = 'Kyros123@';

    // private const NOT_BLANK_MESSAGE = 'Veuillez ajouter un nom d\'utilisateur';

    // private const PASSWORD_REGEX_CONSTRAINT_MESSAGE = "Le mot de passe doit comporter au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial";

    // private ValidatorInterface $validator;

    // protected function setUp(): void
    // {
    //     $kernel = self::bootKernel();

    //     $validatorBuilder = Validation::createValidatorBuilder();
    //     $validatorBuilder->enableAnnotationMapping();
    //     $validator = $validatorBuilder->getValidator();
        
    //     $this->validator = $validator;
    // }

    // public function testAppUtilisateurIsValid(): void
    // {
    //     $user = new AppsUtilisateur;

    //     $user
    //         ->setNomUtilisateur(self::VALID_NOM_UTILISATEUR)
    //         ->setPassword(self::VALID_PASSWORD)
    //         ;
        
    //         $this->getValidationErrors($user, 0);
    // }

    // private function getValidationErrors(AppsUtilisateur $user, int $numberOfExpectedErrors): ConstraintViolationList
    // {
    //     $errors = $this->validator->validate($user);

    //     $this->assertCount($numberOfExpectedErrors, $errors);

    //     return $errors;
    // }
}
