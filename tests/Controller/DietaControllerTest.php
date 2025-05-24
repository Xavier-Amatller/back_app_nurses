<?php

namespace App\Tests\Controller;

use App\Entity\Dieta;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DietaControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $dietumRepository;
    private string $path = '/dieta/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->dietumRepository = $this->manager->getRepository(Dieta::class);

        foreach ($this->dietumRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Dietum index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'dietum[Die_Autonomo]' => 'Testing',
            'dietum[Die_Protesi]' => 'Testing',
            'dietum[registro]' => 'Testing',
            'dietum[Die_TText]' => 'Testing',
            'dietum[Tipos_Dietas]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->dietumRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Dieta();
        $fixture->setDie_Autonomo('My Title');
        $fixture->setDie_Protesi('My Title');
        $fixture->setRegistro('My Title');
        $fixture->setDie_TText('My Title');
        $fixture->setTipos_Dietas('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Dietum');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Dieta();
        $fixture->setDie_Autonomo('Value');
        $fixture->setDie_Protesi('Value');
        $fixture->setRegistro('Value');
        $fixture->setDie_TText('Value');
        $fixture->setTipos_Dietas('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'dietum[Die_Autonomo]' => 'Something New',
            'dietum[Die_Protesi]' => 'Something New',
            'dietum[registro]' => 'Something New',
            'dietum[Die_TText]' => 'Something New',
            'dietum[Tipos_Dietas]' => 'Something New',
        ]);

        self::assertResponseRedirects('/dieta/');

        $fixture = $this->dietumRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDie_Autonomo());
        self::assertSame('Something New', $fixture[0]->getDie_Protesi());
        self::assertSame('Something New', $fixture[0]->getRegistro());
        self::assertSame('Something New', $fixture[0]->getDie_TText());
        self::assertSame('Something New', $fixture[0]->getTipos_Dietas());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Dieta();
        $fixture->setDie_Autonomo('Value');
        $fixture->setDie_Protesi('Value');
        $fixture->setRegistro('Value');
        $fixture->setDie_TText('Value');
        $fixture->setTipos_Dietas('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/dieta/');
        self::assertSame(0, $this->dietumRepository->count([]));
    }
}
