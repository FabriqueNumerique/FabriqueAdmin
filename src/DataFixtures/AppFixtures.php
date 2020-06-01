<?php

namespace App\DataFixtures;

use App\Entity\Absence;
use Faker\Factory;
use App\Entity\Apprenant;
use App\Entity\Contacts;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Entity\Offres;
use App\Entity\PromoAppre;
use App\Entity\Promotion;
use App\Entity\Retard;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
        
    }
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        

        for ($i = 1; $i < 6; $i++){
            $formation = new Formation();
            $formation  ->setIntitule('Développeur web et web mobile'.$i)
                        ->setDuree('8 mois')
                        ->setNombreHeure(mt_rand(1200, 1600))
                        ->setDiplome('Bac +' .$i)
                        ->setModalites($faker->text);
            $manager->persist($formation);

            $promotion = new Promotion();
            $promotion  ->setAnnee(mt_rand(2019,2020))
                        ->setDateDebut($faker->dateTimeBetween('2019-01-01', '2019-12-31'))
                        ->setDateFin($faker->dateTimeBetween('2020-01-01', '+4 months'))
                        ->setFormation($formation)
                        ->setCommentaires($faker->text);
            

            for ($j = 0; $j < (mt_rand(12,22)); $j++) {
                $apprenant = new Apprenant();
                $apprenant  ->setNom($faker->firstName)
                            ->setPrenom($faker->LastName)
                            ->setEmail($faker->email)
                            ->setPassword($this->encoder->encodePassword($apprenant, 'aaaaaa'));
                $apprenant->setTel($faker->mobileNumber)
                            ->setVille($faker->city)
                            ->setDateNaissance($faker->dateTimeBetween('-40 years', '-20 years'))
                            ->setAdresse($faker->streetAddress)
                            ->setStatus('old');

                $manager->persist($apprenant);

                $promoAppre=new PromoAppre();
                $promoAppre ->setPromotion($promotion)
                            ->setApprenant($apprenant);
                $manager->persist($promoAppre);
               

                for ($k = 0; $k < (mt_rand(0, 4)); $k++){
                    $retard = new Retard();
                    $retard ->setDate($faker->dateTimeBetween($promotion->getDateDebut(), $promotion->getDateFin()))
                            ->setNombreHeure(mt_rand(1, 3))
                            ->setJustifie('oui')
                            ->setPromoAppre($promoAppre);


                    $manager->persist($retard);

                    $absence = new Absence();
                    $absence->setDateDebut($faker->dateTimeBetween($promotion->getDateDebut(), $promotion->getDateFin()))
                        ->setDateFin($absence->getDateDebut(),'+2 days')
                        ->setJustifie('oui')
                        ->setPromoAppre($promoAppre);
                    $manager->persist($absence);
                }
            }
            $manager->persist($promotion);

                
        }

        for ($i=0;$i<10;$i++){
            $entreprise = new Entreprise();
            $entreprise->setNom($faker->company)
                ->setTel($faker->phoneNumber)
                ->setMobile($faker->mobileNumber)
                ->setEmail($faker->companyEmail)
                ->setVille($faker->city)
                ->setAdresse($faker->streetAddress)
                ->setCodePostal(mt_rand('33000', '90000'));
            $manager->persist($entreprise); 
            
            for ($j = 0; $j < (mt_rand(3,7)); $j++){
                $contact = new Contacts();
                $contact    ->setNom($faker->firstName)
                            ->setPrenom($faker->LastName)
                            ->setEmail($faker->email)
                            ->setTel($faker->phoneNumber)
                            ->setMobile($faker->mobileNumber)
                            ->setEntreprise($entreprise);
                $manager->persist($contact);
            }

            for ($j = 0; $j < (mt_rand(1,3)); $j++){
                $offre = new Offres();
                $offre  ->setIntitule('stage')
                        ->setDateDebut($faker->dateTimeBetween('2019-01-01', '2020-12-31'))
                        ->setDateFin($faker->dateTimeBetween($promotion->getDateDebut(), '+1 months'))
                        ->setCahierDesCharges($faker->text)
                        ->setEntreprise($entreprise);
                $manager->persist($offre);

            }
            

        }
        for ($i = 0 ; $i < 3 ; $i ++){
            $user = new User();
            if ($i == 0){
                $user   ->setEmail('admin@admin.fr')
                        ->setPassword($this->encoder->encodePassword($apprenant, 'aaaaaa'))
                        ->setRoles(['ROLE_ADMIN']);
                $manager->persist($user);
            }
            if ($i == 1) {
                $user->setEmail('editor@editor.fr')
                    ->setPassword($this->encoder->encodePassword($apprenant, 'aaaaaa'))
                    ->setRoles(['ROLE_EDITOR']);
                $manager->persist($user);
            }
            if ($i == 2) {
                $user->setEmail('reserve@reserve.fr')
                    ->setPassword($this->encoder->encodePassword($apprenant, 'aaaaaa'))
                    ->setRoles(['ROLE_RESERVE']);
                $manager->persist($user); 
            }
        }

        $manager->flush();

    }
}
