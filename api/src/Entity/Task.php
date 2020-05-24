<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="task", indexes={
 *     @ORM\Index(name="k_task_name", columns={"task_name"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Task
{
    /**
     * @ORM\Column(name="task_id", type="smallint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="task_name", type="string", length=128, nullable=false)
     *
     * @Assert\NotBlank(message="Task name cannot be blank.")
     * @Assert\Length(max="128", maxMessage="Task name cannot exceed {{ limit }} characters.")
     *
     * @JMS\Type("string")
     */
    private string $name = '';

    /**
     * @ORM\Column(name="is_completed", type="boolean", nullable=false)
     *
     * @JMS\Type("boolean")
     */
    private bool $isCompleted = false;

    /**
     * @ORM\Column(name="task_date", type="datetime", nullable=true)
     *
     * @JMS\Type("DateTime")
     */
    private ?\DateTime $date = null;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @JMS\Exclude()
     */
    private \DateTime $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TaskLabel")
     * @ORM\JoinTable(
     *     name="task_label_map",
     *     joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="task_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="task_label_id", referencedColumnName="task_label_id")}
     * )
     * @ORM\OrderBy({"displayOrder"="ASC"})
     *
     * @JMS\Exclude()
     */
    private Collection $labels;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

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
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     */
    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    /**
     * @param bool $isCompleted
     */
    public function setIsCompleted(bool $isCompleted): void
    {
        $this->isCompleted = $isCompleted;
    }
}
