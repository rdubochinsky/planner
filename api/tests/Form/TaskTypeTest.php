<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $requestData = [
            'name' => 'Updated Task Name',
            'date' => '2020-05-23',
            'isCompleted' => true,
        ];

        $task = new Task();

        $form = $this->factory->create(TaskType::class, $task);
        $form->submit($requestData);

        $this->assertTrue($task->isCompleted());
        $this->assertEquals($task->getName(), $requestData['name']);
        $this->assertEquals(
            $task->getDate()->format('Y-m-d'),
            (new \DateTime($requestData['date']))->format('Y-m-d')
        );
    }

    public function testSubmitSubmitNonExistingFieldData()
    {
        $requestData = [
            'nonExistingField' => 'Non existing field value',
        ];

        $form = $this->factory->create(TaskType::class, new Task());
        $form->submit($requestData);

        $this->assertFalse($form->isValid());
    }

    protected function getExtensions()
    {
        return [
            new ValidatorExtension(Validation::createValidator()),
            ...parent::getExtensions(),
        ];
    }
}
