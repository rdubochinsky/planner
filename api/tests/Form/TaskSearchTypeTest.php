<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Entity\TaskLabel;
use App\Form\TaskSearchType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class TaskSearchTypeTest extends TypeTestCase
{
    private EntityManager $em;
    private ManagerRegistry $registry;

    private array $taskLabelIds;

    protected function setUp(): void
    {
        $this->em = DoctrineTestHelper::createTestEntityManager();
        $this->registry = $this->createRegistryMock($this->em);

        $this->taskLabelIds = range(1, 5);
        $this->populateTaskLabels();

        parent::setUp();
    }

    public function testSubmitValidName()
    {
        $requestData = [
            TaskSearchType::NAME_FIELD => 'Name length is 128 symbols Name length is 128 symbols '
                . 'Name length is 128 symbols Name length is 128 symbolsName length is 128 sy',
        ];

        $form = $this->factory->create(TaskSearchType::class);
        $form->submit($requestData);

        $this->assertTrue($form->isValid());
    }

    public function testSubmitTooLongName()
    {
        $requestData = [
            TaskSearchType::NAME_FIELD => 'Name length is 129 symbols Name length is 129 symbols '
                . 'Name length is 129 symbols Name length is 129 symbolsName length is 129 sym',
        ];

        $form = $this->factory->create(TaskSearchType::class);
        $form->submit($requestData);

        $this->assertFalse($form->isValid());
    }

    public function testSubmitValidLabels()
    {
        $requestData = [
            TaskSearchType::LABELS_FIELD => [1, 2, 3, 4, 5],
        ];

        $form = $this->factory->create(TaskSearchType::class);
        $form->submit($requestData);

        $this->assertTrue($form->isValid());
    }

    public function testSubmitInvalidLabels()
    {
        $requestData = [
            TaskSearchType::LABELS_FIELD => [6],
        ];

        $form = $this->factory->create(TaskSearchType::class);
        $form->submit($requestData);

        $this->assertFalse($form->isValid());
    }

    public function testSubmitNonExistingFieldData()
    {
        $requestData = [
            'nonExistingField' => 'Non existing field value',
        ];

        $form = $this->factory->create(TaskSearchType::class);
        $form->submit($requestData);

        $this->assertFalse($form->isValid());
    }

    protected function createRegistryMock()
    {
        $registry = $this->getMockBuilder(ManagerRegistry::class)->getMock();
        $registry->expects($this->any())
            ->method('getManager')
            ->willReturn($this->em);

        $registry->expects($this->any())
            ->method('getManagerForClass')
            ->willReturn($this->em);

        return $registry;
    }

    protected function getExtensions()
    {
        return [
            new DoctrineOrmExtension($this->registry),
            new ValidatorExtension(Validation::createValidator()),
            ...parent::getExtensions(),
        ];
    }

    private function populateTaskLabels(): void
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->createSchema([$this->em->getClassMetadata(TaskLabel::class)]);

        foreach ($this->taskLabelIds as $taskLabelId) {
            $this->em->persist($this->createTaskLabel($taskLabelId));
        }

        $this->em->flush();
    }

    /**
     * @param int $id
     *
     * @return TaskLabel
     */
    private function createTaskLabel(int $id): TaskLabel
    {
        $taskLabel = new TaskLabel();
        $reflectionClass = new \ReflectionClass($taskLabel);

        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($taskLabel, $id);

        $handleProperty = $reflectionClass->getProperty('handle');
        $handleProperty->setAccessible(true);
        $handleProperty->setValue($taskLabel, sprintf('handle%d', $id));

        return $taskLabel;
    }
}
