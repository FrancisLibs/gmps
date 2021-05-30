<?php

namespace App\DataFixtures;

use Faker;
use DateInterval;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Option;
use App\Entity\Account;
use App\Entity\Contact;
use App\Entity\Invoice;
use App\Entity\Provider;
use App\Entity\DeliveryForm;
use App\Repository\AccountRepository;
use App\Repository\ProviderRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $orderNumber = 1000;
    private $providerRepository;
    private $accountRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ProviderRepository $providerRepository, AccountRepository $accountRepository)
    {
        $this->encoder = $passwordEncoder;
        $this->providerRepository = $providerRepository;
        $this->accountRepository = $accountRepository;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // Account
        $codes=['I', 'P', 'D', 'S', 'M'];
        $designation=['Investissement', 'Petit matériel', 'Pièce détachée', 'Service', 'Main d\'oeuvre'];
        $i=0;
        foreach($codes as $code)
        {
            $account = new Account();
            $account->setDesignation($designation[$i]);
            $account->setLetter($code);
            $i++;
            $manager->persist($account);
        }

        // PROVIDERS
        $providers=['Sarmabo', 'Efrapo', 'Alpina', 'Mabéo', 'Orexad'];
        foreach($providers as $provider)
        {
            $provider = (new Provider())
                ->setUsername($provider)
                ->setEmail($provider . '@gmail.com')
            ;

            // CONTACTS
            $gender = array('male', 'female');
            for($j=0; $j<(rand(1,4)); $j++)
            {
                $contact = new Contact();
                $contact->setGender(array_rand($gender, 1));
                $contact->setLastName($faker->lastName);
                $contact->setFirstName($faker->firstName);
                $contact->setProvider($provider);
                $provider->addContact($contact);
                $manager->persist($contact);
            }
            $manager->persist($provider);
        }
        $manager->flush();

        //USERS
        for($i=0;$i<6;$i++){
            $option = new Option();
            $user  = (new User())
                ->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setPhoneNumber($faker->phoneNumber)
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName);
                $user->setPassword($this->encoder->encodePassword($user,'password'));
                $option->setDisplayOrderList(true);
                $option->setUser($user);
                $manager->persist($option);
            switch ($i) 
            {
                case 0:
                    $user->setUsername('user');
                    $user->setRoles(['ROLE_USER']);
                    break;
                case 1:
                    $user->setUsername('admin');
                    $user->setRoles(['ROLE_ADMIN']);
                    break;
                case 2:
                    $user->setUsername('supadmin');
                    $user->setRoles(['ROLE_SUPADMIN']);
                    break;
            }

            // ORDERS
            $first = "";
            $status = [Order::EN_COURS, Order::EN_ATTENTE, Order::CLOTUREE];
            for($k=0; $k<3; $k++)
            {
                $order = (new Order())
                    ->setOrderNumber($this->orderNumber)
                    ->setCreatedAt(new \DateTime())
                    ->setExpectedAmount($faker->randomFloat(2, 20, 3000))
                    ->setDesignation($faker->sentence(3), true)
                    ->setStatus(array_rand($status))
                ;
                $expectedDeliveryDate = new \DateTime();
                $quinzeJours=new DateInterval('P15D');
                $expectedDeliveryDate->add($quinzeJours);
                $order->setExpectedDeliveryDate($expectedDeliveryDate);
                $order->setUser($user);
                $user->addOrder($order);
                // Povider
                $providers=$this->providerRepository->findAll();
                foreach($providers as $provider)
                {
                    if(empty($first)){$first=$provider->getId();}
                    $last = $provider->getId();
                }
                $provider=$this->providerRepository->findOneById(rand($first,$last));
                $order->setProvider($provider);
                $provider->addOrder($order);
                //Account
                $firstAccount = "";
                $accounts=$this->accountRepository->findAll();
                foreach($accounts as $account)
                {
                    if(empty($firstAccount)){$firstAccount=$account->getId();}
                    $lastAccount = $account->getId();
                }
                for($m=0; $m<(rand(1,3)); $m++)
                {
                    $account=$this->accountRepository->findOneById(rand($firstAccount,$lastAccount));
                    $order->addAccount($account);
                    $account->addOrder($order);
                    $manager->persist($account);
                }

                // DELIVERY FORM
                for($l=0; $l<(rand(1,3)); $l++)
                {
                    $deliveryForm = (new DeliveryForm())
                        ->setDeliveryFormNumber($faker->randomNumber(5))
                        ->setDeliveryFormDate(new \DateTime())
                        ->setOrder($order)
                    ;
                    $order->addDeliveryForm($deliveryForm);
                    $manager->persist($deliveryForm);
                }
                
                // INVOICE
                for($l=0; $l<(rand(1,2)); $l++)
                {
                    $invoice = (new Invoice())
                        ->setInvoiceNumber($faker->randomNumber(5))
                        ->setInvoiceDate(new \DateTime)
                        ->setAmount($faker->randomNumber(rand(1,5)))
                        ->addOrder($order)
                    ;
                    $order->addDeliveryForm($deliveryForm);
                    $manager->persist($deliveryForm);
                    $manager->persist($invoice);
                }
                $this->orderNumber++;
                $manager->persist($order);
            }
            $manager->persist($user);
        }
        $manager->flush();
    }
}