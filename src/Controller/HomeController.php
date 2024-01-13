<?php
/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 13/01/2024
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', 'home_page', methods: 'GET')]
    public function homePage(Request $request): Response
    {
        return $this->render('homepage/index.html.twig');
    }

    public function handleFeatureRequest()
    {

    }
}