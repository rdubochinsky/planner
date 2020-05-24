<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="task_label", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uk_task_label_handle", columns={"task_label_handle"})
 * })
 * @ORM\Entity
 */
class TaskLabel
{
    /**
     * @ORM\Column(name="task_label_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="task_label_name", type="string", length=24, nullable=false)
     */
    private string $name = '';

    /**
     * @ORM\Column(name="task_label_handle", type="string", length=24, nullable=false)
     */
    private string $handle = '';

    /**
     * @ORM\Column(name="display_order", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private int $displayOrder = 0;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @return int
     */
    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }
}
