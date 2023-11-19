<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{

    public function testRegistrationFormRendering()
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
    }


    public function testSuccessfulRegistration()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form();

        $form['registration_form[email]'] = 'test1@example.com';
        $form['registration_form[plainPassword][first]'] = '12345678';
        $form['registration_form[plainPassword][second]'] = '12345678';
        $form['registration_form[agreeTerms]'] = True;
        $client->submit($form);

        $this->assertResponseRedirects('/');
    }


    public function testFailedRegistration()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form();

        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[plainPassword][first]'] = 'password1';
        $form['registration_form[plainPassword][second]'] = 'password';
        $form['registration_form[agreeTerms]'] = True;

        $client->submit($form);
        $this->assertSelectorTextContains('div.invalid-feedback', 'The password fields must match.');
    }
}
