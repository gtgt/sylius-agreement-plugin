<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver\AgreementApproval;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;

final class AgreementApprovalResolver implements AgreementApprovalResolverInterface
{
    private AgreementHistoryResolverInterface $agreementHistoryResolver;

    public function __construct(AgreementHistoryResolverInterface $agreementHistoryResolver)
    {
        $this->agreementHistoryResolver = $agreementHistoryResolver;
    }

    public function resolve(AgreementInterface $agreement): bool
    {
        $agreementHistory = $this->agreementHistoryResolver->resolveHistory($agreement);

        if ($agreementHistory instanceof AgreementHistoryInterface) {
            return AgreementHistoryStates::STATE_ACCEPTED === $agreementHistory->getState();
        }

        return false;
    }

    public function supports(AgreementInterface $agreement): bool
    {
        return $this->agreementHistoryResolver->supports($agreement);
    }
}
