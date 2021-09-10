<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\AbstractTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="bitbag_sylius_agreement_plugin_agreement_translation")
 */
class AgreementTranslation extends AbstractTranslation implements AgreementTranslationInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected ?int $id = null;

    /**
     * var string
     * @ORM\Column(type="string", name="name")
     */
    protected string $name = '';

    /**
     * @ORM\Column(type="text", name="body")
     */
    protected string $body = '';

    /**
     * @ORM\Column(type="text", nullable=true, name="extended_body")
     */
    protected ?string $extendedBody = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getExtendedBody(): ?string
    {
        return $this->extendedBody;
    }

    public function setExtendedBody(?string $extendedBody): void
    {
        $this->extendedBody = $extendedBody;
    }
}
