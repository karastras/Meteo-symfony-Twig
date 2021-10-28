<?php

namespace App\Controller;

use App\Model\WeatherModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/', name: 'weather_index')]
    public function index(SessionInterface $session): Response
    {
        $datas = WeatherModel::getWeatherData();
        $selectedCity = $session->get('selected_city');

        return $this->render('weather/index.html.twig', [
            'datas' => $datas,
            'selectedCity' => $selectedCity
        ]);
    }

    #[Route('/mountain', name: 'weather_mountain')]
    public function mountain(SessionInterface $session): Response
    {
        $selectedCity = $session->get('selected_city');

        return $this->render('weather/mountain.html.twig', [
            'selectedCity' => $selectedCity,
        ]);
    }

    #[Route('/beach', name: 'weather_beach')]
    public function beach(SessionInterface $session): Response
    {
        $selectedCity = $session->get('selected_city');

        return $this->render('weather/beach.html.twig', [
            'selectedCity' => $selectedCity,
        ]);
    }

    /**
     * 
     * @Route("/select_city/{index}", name="weather_select", requirements={"index"="\d+"})
     */
    public function selectCity($index, SessionInterface $session)
    {
        // 1) On récupère les données associées à l'index
        $selectedCityData = WeatherModel::getWeatherByCityIndex($index);
    
        if ($selectedCityData === false) {
            // La ville n'existe pas en base
            $this->addFlash('warning', "La ville n° $index n'existe pas");
        } else {
            // Tout s'est bien passé
            // 2) On met en session les données récupérées
            $session->set('selected_city', $selectedCityData);
        }
        
        // 3) On redirige vers la page d'accueil
        return $this->redirectToRoute('weather_index');
    }
}
