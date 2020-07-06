<?php


namespace App\Controller;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordAction
{

    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordEncoder
     */
    private $userPasswordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTTokenManager;


    public function __construct(ValidatorInterface $validator, UserPasswordEncoderInterface $userPasswordEncoder,
                                EntityManagerInterface $em,
                                JWTTokenManagerInterface $JWTTokenManager)
    {

        $this->validator = $validator;
       //    dump($validator);die;
        $this->userPasswordEncoder = $userPasswordEncoder;

        $this->em = $em;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    public function __invoke(User $data)
    {
        // TODO: Implement __invoke() method.
      /*  dump($data->getNewPassword(),
            $data->getNewretypePassword(),
            $data->getOldPassword(),
            $data->getRetypePassword(),
            $data->getPassword()

        );
        die;*/
      //  dump($data);
      // dump($this->validator->validate($data));die;
        $this->validator->validate($data);

       $data->setPassword(
            $this->userPasswordEncoder->encodePassword(
                $data, $data->getNewPassword()
            )
        );
       // dump($data);die;
        $data->setPasswordChangeDate(time());
        $this->em->flush();
        $token = $this->JWTTokenManager->create($data);
        return new JsonResponse(['token'=>$token]);

    }
}