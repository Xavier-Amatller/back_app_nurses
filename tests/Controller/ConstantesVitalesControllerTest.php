<?php

namespace App\Tests\Controller;

use App\Entity\ConstantesVitales;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ConstantesVitalesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $constantesVitaleRepository;
    private string $path = '/constantes/vitales/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->constantesVitaleRepository = $this->manager->getRepository(ConstantesVitales::class);

        foreach ($this->constantesVitaleRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ConstantesVitale index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'constantes_vitale[cv_ta_sistolica]' => 'Testing',
            'constantes_vitale[cv_ta_diastolica]' => 'Testing',
            'constantes_vitale[cv_frequencia_respiratoria]' => 'Testing',
            'constantes_vitale[cv_pulso]' => 'Testing',
            'constantes_vitale[cv_temperatura]' => 'Testing',
            'constantes_vitale[cv_saturacion_oxigeno]' => 'Testing',
            'constantes_vitale[cv_talla]' => 'Testing',
            'constantes_vitale[cv_diuresis]' => 'Testing',
            'constantes_vitale[cv_deposiciones]' => 'Testing',
            'constantes_vitale[cv_stp]' => 'Testing',
            'constantes_vitale[cv_timestamp]' => 'Testing',
            'constantes_vitale[registro]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->constantesVitaleRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new ConstantesVitales();
        $fixture->setCv_ta_sistolica('My Title');
        $fixture->setCv_ta_diastolica('My Title');
        $fixture->setCv_frequencia_respiratoria('My Title');
        $fixture->setCv_pulso('My Title');
        $fixture->setCv_temperatura('My Title');
        $fixture->setCv_saturacion_oxigeno('My Title');
        $fixture->setCv_talla('My Title');
        $fixture->setCv_diuresis('My Title');
        $fixture->setCv_deposiciones('My Title');
        $fixture->setCv_stp('My Title');
        $fixture->setCv_timestamp('My Title');
        $fixture->setRegistro('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ConstantesVitale');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new ConstantesVitales();
        $fixture->setCv_ta_sistolica('Value');
        $fixture->setCv_ta_diastolica('Value');
        $fixture->setCv_frequencia_respiratoria('Value');
        $fixture->setCv_pulso('Value');
        $fixture->setCv_temperatura('Value');
        $fixture->setCv_saturacion_oxigeno('Value');
        $fixture->setCv_talla('Value');
        $fixture->setCv_diuresis('Value');
        $fixture->setCv_deposiciones('Value');
        $fixture->setCv_stp('Value');
        $fixture->setCv_timestamp('Value');
        $fixture->setRegistro('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'constantes_vitale[cv_ta_sistolica]' => 'Something New',
            'constantes_vitale[cv_ta_diastolica]' => 'Something New',
            'constantes_vitale[cv_frequencia_respiratoria]' => 'Something New',
            'constantes_vitale[cv_pulso]' => 'Something New',
            'constantes_vitale[cv_temperatura]' => 'Something New',
            'constantes_vitale[cv_saturacion_oxigeno]' => 'Something New',
            'constantes_vitale[cv_talla]' => 'Something New',
            'constantes_vitale[cv_diuresis]' => 'Something New',
            'constantes_vitale[cv_deposiciones]' => 'Something New',
            'constantes_vitale[cv_stp]' => 'Something New',
            'constantes_vitale[cv_timestamp]' => 'Something New',
            'constantes_vitale[registro]' => 'Something New',
        ]);

        self::assertResponseRedirects('/constantes/vitales/');

        $fixture = $this->constantesVitaleRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getCv_ta_sistolica());
        self::assertSame('Something New', $fixture[0]->getCv_ta_diastolica());
        self::assertSame('Something New', $fixture[0]->getCv_frequencia_respiratoria());
        self::assertSame('Something New', $fixture[0]->getCv_pulso());
        self::assertSame('Something New', $fixture[0]->getCv_temperatura());
        self::assertSame('Something New', $fixture[0]->getCv_saturacion_oxigeno());
        self::assertSame('Something New', $fixture[0]->getCv_talla());
        self::assertSame('Something New', $fixture[0]->getCv_diuresis());
        self::assertSame('Something New', $fixture[0]->getCv_deposiciones());
        self::assertSame('Something New', $fixture[0]->getCv_stp());
        self::assertSame('Something New', $fixture[0]->getCv_timestamp());
        self::assertSame('Something New', $fixture[0]->getRegistro());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new ConstantesVitales();
        $fixture->setCv_ta_sistolica('Value');
        $fixture->setCv_ta_diastolica('Value');
        $fixture->setCv_frequencia_respiratoria('Value');
        $fixture->setCv_pulso('Value');
        $fixture->setCv_temperatura('Value');
        $fixture->setCv_saturacion_oxigeno('Value');
        $fixture->setCv_talla('Value');
        $fixture->setCv_diuresis('Value');
        $fixture->setCv_deposiciones('Value');
        $fixture->setCv_stp('Value');
        $fixture->setCv_timestamp('Value');
        $fixture->setRegistro('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/constantes/vitales/');
        self::assertSame(0, $this->constantesVitaleRepository->count([]));
    }
}
