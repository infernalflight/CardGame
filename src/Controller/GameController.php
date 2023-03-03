<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\DrawType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function mysql_xdevapi\getSession;

class GameController extends AbstractController
{
    public const COLORS = ['Carreau', 'Coeur', 'Pique', 'Trèfle'];

    public const VALUES = ['2', '3', '4', '5', '6', '7', '8', '9' , '10', 'Valet', 'Dame', 'Roi', 'As'];

    #[Route('/', name: 'game')]
    public function startAction(Request $request): Response
    {
        $session = $request->getSession();
        if (empty($session->get('randomized_colors')) || empty($session->get('randomized_values'))) {
            $randomizedColors = self::COLORS;
            $randomizedValues = self::VALUES;

            shuffle($randomizedColors);
            shuffle($randomizedValues);

            $session->set('randomized_colors', $randomizedColors);
            $session->set('randomized_values', $randomizedValues);
        }

        $randomizedColors = $session->get('randomized_colors');
        $randomizedValues = $session->get('randomized_values');

        $drawForm = $this->createForm(DrawType::class);

        $drawnCards = $sortedCards = [];

        if ('POST' === $request->getMethod()) {
            $drawForm->handleRequest($request);
            if ($drawForm->isSubmitted() && $drawForm->isValid()) {
                $values = $drawForm->getData();
                if (is_numeric($values['number']) && $values['number'] > 0 && $values['number'] < 53) {
                    $drawnColors = self::COLORS;
                    $drawnValues = self::VALUES;

                    shuffle($drawnColors);
                    shuffle($drawnValues);

                    foreach ($randomizedColors as $color) {
                        foreach ($randomizedValues as $value) {
                            $cards[] = new Card($color, $value);
                        }
                    }

                    shuffle($cards);

                    $drawnCards = array_slice($cards, 0, $values['number']);

                    foreach ($drawnCards as &$card) {
                        $card->setIndexColor(array_search($card->getColor(), $randomizedColors));
                        $card->setIndexValue(array_search($card->getvalue(), $randomizedValues));
                    }

                    $sortedCards = $drawnCards;

                    usort($sortedCards, function($a, $b) {
                        //retourner 0 en cas d'égalité
                        if ($a->getIndexColor() == $b->getIndexColor()) {
                            return ($a->getIndexValue() < $b->getIndexValue()) ? -1 : 1;
                        }
                        return ($a->getIndexColor() < $b->getIndexColor()) ? -1 : 1;
                    });
                }
            }
        }

        return $this->render('game.twig', [
            'randomized_colors' => $randomizedColors,
            'randomized_values' => $randomizedValues,
            'drawn_cards'       => $drawnCards,
            'sorted_cards'      => $sortedCards,
            'draw_form'         => $drawForm,
        ]);
    }

    #[Route('/reset', name: 'reset')]
    public function resetSession(Request $request): Response
    {
        $session = $request->getSession();
        if (!empty($session->get('randomized_colors'))) {
            $session->set('randomized_colors', []);
        }

        return $this->redirectToRoute('game');
    }
}