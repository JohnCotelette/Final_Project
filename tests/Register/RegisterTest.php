<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterFormTest extends WebTestCase
{
    public function testRegisterCandidateForm()
    {
        //The createClient() method returns a client
        $client = static::createClient();

        //Crawler object which can be used to select elements in the response,
        //click on links and submit forms.
        $crawler = $client->request('GET', '/candidate/register');

        //Select the button with ID name
        $buttonCrawlerNode = $crawler->selectButton('submit');
        
        //Get form for override some form values and submit the corresponding form
        $form = $buttonCrawlerNode->form();

        $form = $buttonCrawlerNode->form([
            'candidat[firstName]'    => 'Azerty',
            'candidat[lastName]' => 'Uiopqsd',
            'candidat[email]'    => 'Azerty@Azerty.fr',
            'candidat[birthDay][year]' => 1984,
            'candidat[birthDay][month]'=> 2,
            'candidat[birthDay][day]'=> 1,
            'candidat[password][first]' => 'Azerty',
            'candidat[password][second]' => 'Azerty',
            'candidat[legalConditions]' => 1,
        ]);

        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRegisterRecruiterForm()
    {
        //The createClient() method returns a client
        $client = static::createClient();

        //Crawler object which can be used to select elements in the response,
        //click on links and submit forms.
        $crawler = $client->request('GET', '/recruiter/register');

        //Select the button with ID name
        $buttonCrawlerNode = $crawler->selectButton('submit');
        
        //Get form for override some form values and submit the corresponding form
        $form = $buttonCrawlerNode->form();
        $form = $buttonCrawlerNode->form([
            'recruiter[firstName]'    => 'Azerty',
            'recruiter[lastName]' => 'Uiopqsd',
            'recruiter[email]'    => 'Azerty@Azerty.fr',
            'recruiter[birthDay][year]' => 1984,
            'recruiter[birthDay][month]'=> 2,
            'recruiter[birthDay][day]'=> 1,
            'recruiter[password][first]' => 'Azerty',
            'recruiter[password][second]' => 'Azerty',
            'recruiter[business]' => 'WebForce3',
            'recruiter[legalConditions]' => 1,
        ]);

        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testFieldRegisterRecruiterForm()
    {
        //The createClient() method returns a client
        $client = static::createClient();

        //Crawler object which can be used to select elements in the response,
        //click on links and submit forms.
        $crawler = $client->request('GET', '/recruiter/register');

        //Select the button with ID name
        $buttonCrawlerNode = $crawler->selectButton('submit');

        //Get form for override some form values and submit the corresponding form
        $form = $buttonCrawlerNode->form([
            'recruiter[email]' => 'azerty',
        ]);

        //Submit form
        $crawler = $client->submit($form);

        //Get errors 
        $error = $crawler->filter(".field-firstname .error li");

        //Get string
        $error =  $error->getNode(0)->textContent;

        $this->assertEquals($error, 'Veuillez renseigner votre prénom');
    }

    public function testFieldRegisterCandidatForm()
    {
        //The createClient() method returns a client
        $client = static::createClient();

        //Crawler object which can be used to select elements in the response,
        //click on links and submit forms.
        $crawler = $client->request('GET', '/candidate/register');

        //Select the button with ID name
        $buttonCrawlerNode = $crawler->selectButton('submit');

        //Get form for override some form values and submit the corresponding form
        $form = $buttonCrawlerNode->form([
            'candidat[firstName]' => 1245,
        ]);

        //Submit form
        $crawler = $client->submit($form);

        //Get errors 
        $error = $crawler->filter(".field-firstname .error li");

        //Get string
        $error =  $error->getNode(0)->textContent;
        
        $this->assertEquals($error, "N'utilisez pas de caractères spécial");
    }
}
