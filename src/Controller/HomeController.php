<?php
/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 13/01/2024
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class HomeController
 *
 * Default controller, will handle all feature request from end user
 */
class HomeController extends AbstractController
{
    #[Route('/', 'home_page', methods: 'GET')]
    public function homePage(): Response
    {
        return $this->render('homepage/index.html.twig');
    }
}