<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;

/**
 * @method AgreementTranslationInterface getTranslation()
 * @method void addTranslation(AgreementTranslationInterface $translation)
 */
class Agreement implements AgreementInterface
{
    use ToggleableTrait;

    use TimestampableTrait;

    use TranslatableTrait {
        TranslatableTrait::__construct as protected initializeTranslationsCollection;
    }

    protected ?int $id = null;

    protected ?string $code = null;

    protected string $mode = self::MODE_REQUIRED;

    protected ?\DateTime $publishedAt = null;

    /** @var \DateTime|null */
    protected $updatedAt;

    /** @var \DateTime|null */
    protected $createdAt;

    protected array $contexts = [];

    protected ?AgreementInterface $parent = null;

    protected int $orderOnView = 1;

    protected bool $approved = false;

    protected ?\DateTime $archivedAt = null;

    /** @var CustomerInterface|null */
    protected $customers;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->initializeTranslationsCollection();
    }

    public function __clone()
    {
        if (null === $this->id) {
            return;
        }
        $this->id = null;
        $clonedTranslations = new ArrayCollection();
        foreach ($this->translations as $translation) {
            $clonedTranslation = clone $translation;
            $clonedTranslation->setTranslatable($this);
            $clonedTranslations->add($clonedTranslation);
        }
        $this->translations = $clonedTranslations;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public function getBody(): string
    {
        return $this->getTranslation()->getBody();
    }

    public function setBody(string $body): void
    {
        $this->getTranslation()->setBody($body);
    }

    public function getExtendedBody(): ?string
    {
        return $this->getTranslation()->getExtendedBody();
    }

    public function setExtendedBody(?string $body): void
    {
        $this->getTranslation()->setExtendedBody($body);
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): void
    {
        if (null !== $publishedAt) {
            $publishedAt->setTime(0, 0, 0);
        }
        $this->publishedAt = $publishedAt;
    }

    public function getContexts(): array
    {
        return $this->contexts;
    }

    public function setContexts(array $contexts): void
    {
        $this->contexts = $contexts;
    }

    /** @return ?AgreementInterface */
    public function getParent(): ?AgreementInterface
    {
        return $this->parent;
    }

    public function setParent(?AgreementInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getOrderOnView(): int
    {
        return $this->orderOnView;
    }

    public function setOrderOnView(int $orderOnView): void
    {
        $this->orderOnView = $orderOnView;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }

    public function getArchivedAt(): ?\DateTime
    {
        return $this->archivedAt;
    }

    public function setArchivedAt(?\DateTime $archivedAt): void
    {
        $this->archivedAt = $archivedAt;
    }

    public function isReadOnly(): bool
    {
        return AgreementInterface::MODE_ONLY_SHOW === $this->mode;
    }

    /** @return AgreementTranslation */
    protected function createTranslation(): TranslationInterface
    {
        return new AgreementTranslation();
    }
}
