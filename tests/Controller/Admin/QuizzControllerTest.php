<?php

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizzControllerTest extends WebTestCase
{
        /**
     * On liste ici toutes nos pages
     */
    public function urlProvider()
    {
        yield ['/admin/quizzs/'];
        yield ['/admin/world/'];
        yield ['/admin/category/'];
        yield ['/admin/puzzle/'];
        yield ['/admin/users/'];
    }

    /**
     * Test de redirection vers login en cas d'accès à une page protégée sans être connecté
     * @dataProvider urlProvider
     */
    public function testAnonymousAccessBackendRedirectToLogin($url)
    {
        // Création d'un client web
        $client = static::createClient();
        // Autorisation du suivi des redirections sinon il restera bloqué
        $client->followRedirects();
        // Acces à la page admin Quizzs nécessitant d'être connecté
        $client->request('GET', $url);
        // les redirections sont suivis donc on vérifie qu'on est bien sur la page login
        $this->assertContains(
            'Please sign in',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Test de la page listant les quizzs
     * nécessite d'être connecté en admin, le login doit être submit
     */
    public function testLoginAdminAccessQuizzIndex()
    {
        // Création d'un client web
        $client = static::createClient();
        // Autorisation du suivi des redirections sinon il restera bloqué
        $client->followRedirects();
        // Acces à la page admin Quizzs nécessitant d'être connecté
        $crawler = $client->request('GET', '/admin/quizzs/');
        // Vérification si l'on arrive bien sur une page existante, statut 200
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Vérification si l'on est bien sur la page de login, on teste si le bouton sign in est présent
        // et on remplis les champs du formulaire
        $form = $crawler->selectButton('Sign in')->form([
            'email' => 'lorenzo.marozzo@gmail.com',
            'password' => 'ochildren',
        ]);
        // on soumet le formulaire
        $crawler = $client->submit($form);
        // on est de nouveau redirigé vers la gestion des quizzs donc je vérifie que je suis sur la bonne page
        // en vérifiant si le titre "Gestion des quizzs" est présent
        $this->assertContains('Gestion des quizzs', $crawler->filter('.container-fluid h1')->text());
    }

    /**
     * Test de la page d'ajout de quizz, nécessite toujours une connexion
     */
    public function testAdminQuizzAdd()
    {
        // Création d'un client web
        $client = static::createClient();
        // Autorisation du suivi des redirections sinon il restera bloqué
        $client->followRedirects();
        // Acces à la page admin Quizzs nécessitant d'être connecté
        $crawler = $client->request('GET', '/admin/quizzs/');
        // Vérification si l'on est bien sur la page de login, on teste si le bouton sign in est présent
        // et on remplis les champs du formulaire
        $form = $crawler->selectButton('Sign in')->form([
            'email' => 'lorenzo.marozzo@gmail.com',
            'password' => 'ochildren',
        ]);
        // on soumet le formulaire
        $crawler = $client->submit($form);

        // Sélection du bouton/lien "Create New"
        $link = $crawler->filter('a.btn-outline-success')->link();
        // Click sur le lien
        $crawler = $client->click($link);
        // Un premier test qui vérifie que la page répond correctement
        // 200 = OK = pas d'erreur 404, par d'erreur 500 etc.
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        // Vérifier que le titre de la page correspond au titre du lien cliqué
        $this->assertContains('Create new Quizz', $crawler->filter('.container-fluid h1')->text());
        // Vérification si l'on est bien sur la page d'ajout de quiz, on teste si le bouton save est présent
        // et on remplis les champs obligatoire du formulaire
        $form = $crawler->selectButton('Save')->form([
            'quizz[title]' => 'ajout de quiz via un test unitaire',
            'quizz[description]' => 'elle est optionnelle mais je teste tout de même pour le test',
            'quizz[world]' => 7,
        ]);
        // on soumet le formulaire
        $crawler = $client->submit($form);
        // on est de nouveau redirigé vers la gestion des quizzs donc je vérifie que je suis sur la bonne page
        // en vérifiant si le titre "Gestion des quizzs" est présent
        $this->assertContains('Gestion des quizzs', $crawler->filter('.container-fluid h1')->text());
        // on vérifie que le titre ajouté précédemment est sur la page
        $this->assertContains(
            'ajout de quiz via un test unitaire',
            $client->getResponse()->getContent()
        );
    }
}