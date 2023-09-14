<?php

namespace App\Entity;

use App\Repository\TricountsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TricountsRepository::class)]
class Tricounts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'tricounts', cascade:['persist','remove'])]
    #[ORM\JoinTable(name: 'tricounts_users')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Collection $user;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(length: 255)]
    private ?string $devise = 'euro';

    #[ORM\OneToMany(mappedBy: 'tricount', targetEntity: Expenses::class, orphanRemoval: true)]
    private Collection $expenses;


    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->expenses = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Users $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): static
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * @return Collection<int, Expenses>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expenses $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setTricount($this);
        }

        return $this;
    }

    public function removeExpense(Expenses $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getTricount() === $this) {
                $expense->setTricount(null);
            }
        }

        return $this;
    }

    public function getUsersWithBalances(): array
    {
        $usersWithBalances = [];

        foreach ($this->user as $user) {
            $balance = 0;

            foreach ($this->expenses as $expense) {
                if ($expense->getUser() === $user) {
                    $balance += $expense->getValue();
                }
            }

            $usersWithBalances[] = [
                'user' => $user,
                'balance' => $balance,
            ];
        }

        return $usersWithBalances;
    }

    public function countUsers(): int
    {
        return $this->user->count();
    }

}
