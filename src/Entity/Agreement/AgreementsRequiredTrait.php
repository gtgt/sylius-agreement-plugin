<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\Collection;

trait AgreementsRequiredTrait
{
    /** @var ?Collection|AgreementInterface[] */
    protected $agreements;

    public function getAgreements(): ?Collection
    {
        return $this->agreements;
    }

    public function setAgreements(?Collection $agreements): void
    {
        $this->agreements = $agreements;
    }
}
