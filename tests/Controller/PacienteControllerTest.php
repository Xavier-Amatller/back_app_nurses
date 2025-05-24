<?php

namespace App\Tests\Controller;

use App\Entity\Paciente;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PacienteControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $pacienteRepository;
    private string $path = '/paciente/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->pacienteRepository = $this->manager->getRepository(Paciente::class);

        foreach ($this->pacienteRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Paciente index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'paciente[pac_num_historial]' => 'Testing',
            'paciente[pac_nombre]' => 'Testing',
            'paciente[pac_apellidos]' => 'Testing',
            'paciente[pac_fecha_nacimiento]' => 'Testing',
            'paciente[pac_direccion_completa]' => 'Testing',
            'paciente[pac_lengua_materna]' => 'Testing',
            'paciente[pac_antecedentes]' => 'Testing',
            'paciente[pac_alergias]' => 'Testing',
            'paciente[pac_nombre_cuidador]' => 'Testing',
            'paciente[pac_telefono_cuidador]' => 'Testing',
            'paciente[pac_fecha_ingreso]' => 'Testing',
            'paciente[pac_timestamp]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->pacienteRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Paciente();
        $fixture->setPac_num_historial('My Title');
        $fixture->setPac_nombre('My Title');
        $fixture->setPac_apellidos('My Title');
        $fixture->setPac_fecha_nacimiento('My Title');
        $fixture->setPac_direccion_completa('My Title');
        $fixture->setPac_lengua_materna('My Title');
        $fixture->setPac_antecedentes('My Title');
        $fixture->setPac_alergias('My Title');
        $fixture->setPac_nombre_cuidador('My Title');
        $fixture->setPac_telefono_cuidador('My Title');
        $fixture->setPac_fecha_ingreso('My Title');
        $fixture->setPac_timestamp('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Paciente');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Paciente();
        $fixture->setPac_num_historial('Value');
        $fixture->setPac_nombre('Value');
        $fixture->setPac_apellidos('Value');
        $fixture->setPac_fecha_nacimiento('Value');
        $fixture->setPac_direccion_completa('Value');
        $fixture->setPac_lengua_materna('Value');
        $fixture->setPac_antecedentes('Value');
        $fixture->setPac_alergias('Value');
        $fixture->setPac_nombre_cuidador('Value');
        $fixture->setPac_telefono_cuidador('Value');
        $fixture->setPac_fecha_ingreso('Value');
        $fixture->setPac_timestamp('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'paciente[pac_num_historial]' => 'Something New',
            'paciente[pac_nombre]' => 'Something New',
            'paciente[pac_apellidos]' => 'Something New',
            'paciente[pac_fecha_nacimiento]' => 'Something New',
            'paciente[pac_direccion_completa]' => 'Something New',
            'paciente[pac_lengua_materna]' => 'Something New',
            'paciente[pac_antecedentes]' => 'Something New',
            'paciente[pac_alergias]' => 'Something New',
            'paciente[pac_nombre_cuidador]' => 'Something New',
            'paciente[pac_telefono_cuidador]' => 'Something New',
            'paciente[pac_fecha_ingreso]' => 'Something New',
            'paciente[pac_timestamp]' => 'Something New',
        ]);

        self::assertResponseRedirects('/paciente/');

        $fixture = $this->pacienteRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getPac_num_historial());
        self::assertSame('Something New', $fixture[0]->getPac_nombre());
        self::assertSame('Something New', $fixture[0]->getPac_apellidos());
        self::assertSame('Something New', $fixture[0]->getPac_fecha_nacimiento());
        self::assertSame('Something New', $fixture[0]->getPac_direccion_completa());
        self::assertSame('Something New', $fixture[0]->getPac_lengua_materna());
        self::assertSame('Something New', $fixture[0]->getPac_antecedentes());
        self::assertSame('Something New', $fixture[0]->getPac_alergias());
        self::assertSame('Something New', $fixture[0]->getPac_nombre_cuidador());
        self::assertSame('Something New', $fixture[0]->getPac_telefono_cuidador());
        self::assertSame('Something New', $fixture[0]->getPac_fecha_ingreso());
        self::assertSame('Something New', $fixture[0]->getPac_timestamp());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Paciente();
        $fixture->setPac_num_historial('Value');
        $fixture->setPac_nombre('Value');
        $fixture->setPac_apellidos('Value');
        $fixture->setPac_fecha_nacimiento('Value');
        $fixture->setPac_direccion_completa('Value');
        $fixture->setPac_lengua_materna('Value');
        $fixture->setPac_antecedentes('Value');
        $fixture->setPac_alergias('Value');
        $fixture->setPac_nombre_cuidador('Value');
        $fixture->setPac_telefono_cuidador('Value');
        $fixture->setPac_fecha_ingreso('Value');
        $fixture->setPac_timestamp('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/paciente/');
        self::assertSame(0, $this->pacienteRepository->count([]));
    }
}
