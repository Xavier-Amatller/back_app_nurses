<?php

namespace App\Tests\Controller;

use App\Entity\Registro;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegistroControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $registroRepository;
    private string $path = '/registro/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->registroRepository = $this->manager->getRepository(Registro::class);

        foreach ($this->registroRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Registro index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'registro[reg_timestamp]' => 'Testing',
            'registro[aux_id]' => 'Testing',
            'registro[pac_id]' => 'Testing',
            'registro[cv_id]' => 'Testing',
            'registro[die_id]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->registroRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Registro();
        $fixture->setReg_timestamp('My Title');
        $fixture->setAux_id('My Title');
        $fixture->setPac_id('My Title');
        $fixture->setCv_id('My Title');
        $fixture->setDie_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Registro');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Registro();
        $fixture->setReg_timestamp('Value');
        $fixture->setAux_id('Value');
        $fixture->setPac_id('Value');
        $fixture->setCv_id('Value');
        $fixture->setDie_id('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'registro[reg_timestamp]' => 'Something New',
            'registro[aux_id]' => 'Something New',
            'registro[pac_id]' => 'Something New',
            'registro[cv_id]' => 'Something New',
            'registro[die_id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/registro/');

        $fixture = $this->registroRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getReg_timestamp());
        self::assertSame('Something New', $fixture[0]->getAux_id());
        self::assertSame('Something New', $fixture[0]->getPac_id());
        self::assertSame('Something New', $fixture[0]->getCv_id());
        self::assertSame('Something New', $fixture[0]->getDie_id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Registro();
        $fixture->setReg_timestamp('Value');
        $fixture->setAux_id('Value');
        $fixture->setPac_id('Value');
        $fixture->setCv_id('Value');
        $fixture->setDie_id('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/registro/');
        self::assertSame(0, $this->registroRepository->count([]));
    }
}
