<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Keyword;
use App\Entity\Media;
use App\Entity\Set;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    #[Route('/import', name: 'app_import')]
    public function import(EntityManagerInterface $entityManager): Response
    {
        set_time_limit(0);
        $startTimer = microtime(true);

        $allCardData = file_get_contents('./../../cardData.json', FILE_USE_INCLUDE_PATH);
        $allCardData = json_decode($allCardData);
        foreach ($allCardData as $cardData) {
            $card = new Card();

            // Name
            $card->setName($cardData->name);

            // Mana cost
            if (property_exists($cardData,"mana_cost")){
                $card->setCost($cardData->mana_cost);
            } else {
                $card->setCost("");
            }

            // Colors
            if (property_exists($cardData,"colors")){
                $colors = implode('', $cardData->colors);
            } else {
                $colors = "C";
            }
            $card->setColors($colors);

            // Mana value
            if (property_exists($cardData,"cmc")){
                $card->setManaValue($cardData->cmc);
            } else {
                $card->setManaValue(0);
            }

            // Power, toughness, loyalty and defense
            if (property_exists($cardData,"power")){
                $card->setPower(floatval($cardData->power));
            }
            if (property_exists($cardData,"toughness")){
                $card->setToughness(floatval($cardData->toughness));
            }
            if (property_exists($cardData,"loyalty")){
                $card->setLoyalty(intval($cardData->loyalty));
            }
            if (property_exists($cardData,"defense")){
                $card->setDefense(intval($cardData->defense));
            }

            // Type line
            if (property_exists($cardData,"type_line")){
                $card->setTypeline($cardData->type_line);
            } else {
                $card->setTypeline("");
            }

            // Keywords
            foreach ($cardData->keywords as $rawKeyword){
                $keyword = $entityManager->getRepository(Keyword::class)->findOneBy(array('name' => $rawKeyword));
                if (!$keyword){
                    $keyword = new Keyword();
                    $keyword->setName($rawKeyword);
                    $entityManager->persist($keyword);
                    $entityManager->flush();
                }
                $card->addKeyword($keyword);
            }

            // Text effect & Flavor text
            if (property_exists($cardData, "oracle_text")){
                $card->setEffectText($cardData->oracle_text);
            }
            if (property_exists($cardData, "flavor_text")){
                $card->setFlavorText($cardData->flavor_text);
            }

            // Color identity
            if (property_exists($cardData,"color_identity")){
                $colorsIdentities = implode('', $cardData->color_identity);
            } else {
                $colorsIdentities = "C";
            }
            $card->setColorIdentity($colorsIdentities);

            // Rarity
            $card->setRarity($cardData->rarity);

            // Set
            $set = $entityManager->getRepository(Set::class)->findOneBy(array('name' => $cardData->set_name));
            if (!$set){
                $set = new Set();
                $set->setName($cardData->set_name);
                $set->setReleaseDate(new DateTime($cardData->released_at));
                $entityManager->persist($set);
                $entityManager->flush();
            }
            $set->addCard($card);

            // Media
            if(property_exists($cardData,"image_uris")){
                if(property_exists($cardData->image_uris, "normal")){
                    $fullCard = new Media();
                    $fullCard->setAlt($card->getName());
                    $fullCard->setPath($cardData->image_uris->normal);
                    $fullCard->setType("card");
                    $entityManager->persist($fullCard);
                    $card->addMedium($fullCard);
                }
                if (property_exists($cardData->image_uris, "art_crop")) {
                    $croppedArt = new Media();
                    $croppedArt->setAlt($card->getName());
                    $croppedArt->setPath($cardData->image_uris->art_crop);
                    $croppedArt->setType("crop");
                    $entityManager->persist($croppedArt);
                    $card->addMedium($croppedArt);
                }
            }

            $card->setArtist($cardData->artist);
            $entityManager->persist($card);
        }
        $entityManager->flush();

        $endTimer = microtime(true);
        $elapsed = $endTimer-$startTimer;

        $response = new Response();
        return $response->setContent('Import successfully ran in ' . $elapsed/60 . 'minutes.')->setStatusCode(200);
    }
}
