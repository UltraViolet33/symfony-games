<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{

    public function testRegistrationFormRendering()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
        $this->assertCount(1, $crawler->filter('form[name="registration_form"]'));
    }

    // public function testSuccessfulRegistration()
    // {
    //     $client = static::createClient();

    //     $crawler = $client->request('GET', '/register');

    //     $form = $crawler->selectButton('Register')->form();

    //     $form['registration_form[email]'] = 'test@example.com';

    //     $form['registration_form[plainPassword][first]'] = 'password';
    //     $form['registration_form[plainPassword][second]'] = 'password';
    //     $form['registration_form[agreeTerms]'] = True;


    //     $client->submit($form);

    //     $this->assertResponseRedirects('/');
    // }


    // public function testFailedRegistration()
    // {
    //     $client = static::createClient();

    //     $crawler = $client->request('GET', '/register');

    //     $form = $crawler->selectButton('Register')->form();

    //     $form['registration_form[email]'] = 'invalid-email';
    //     $form['registration_form[plainPassword][first]'] = 'password';
    //     $form['registration_form[plainPassword][second]'] = 'password';
    //     $form['registration_form[agreeTerms]'] = True;

    //     $client->submit($form);

    //     $this->assertResponseIsSuccessful();
    //     // $this->assertSelectorTextContains('div.invalid-feedback', 'The password fields must match.');
    //     $this->assertSelectorTextContains('div.invalid-feedback', 'You should agree to our terms.');
    // }
}
