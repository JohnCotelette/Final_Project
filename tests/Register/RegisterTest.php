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
        $this->assertResponseIsSuccessful();
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
            'recruiter[password][first]' => 'Azerty123',
            'recruiter[password][second]' => 'Azerty123',
            'recruiter[business]' => '01234567891011',
            'recruiter[legalConditions]' => 1,
        ]);
        
        $client->submit($form);
        $this->assertResponseIsSuccessful();
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
            'recruiter[password][first]' => 'Azerty',
            'recruiter[password][second]' => 'Azerty',

        ]);

        //Submit form
        $crawler = $client->submit($form);

        //Get errors 
        $error = $crawler->filter("#emailErrorsContainer .field-emailError");
        

        //Get string
        $error =  $error->getNode(0)->textContent;
        dd($this->assertEquals($error, 'Veuillez renseigner une adresse e-mail valide') );
        $this->assertEquals($error, 'Veuillez renseigner une adresse e-mail valide');
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
        $error = $crawler->filter("#identityErrorsContainer .field-firstNameError");
        //Get string
        $error =  $error->getNode(0)->textContent;
        
        $this->assertEquals($error, "N'utilisez pas de caractères spéciaux");
    }
}
