<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Extension;

use BitBag\SyliusAgreementPlugin\Checker\AgreementHistoryCheckerInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Event\AgreementCheckedEvent;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Webmozart\Assert\Assert;

final class AgreementsTypeExtension extends AbstractTypeExtension
{
    private AgreementRepositoryInterface $agreementRepository;

    private AgreementHistoryCheckerInterface $agreementHistoryChecker;

    private array $contexts;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        AgreementRepositoryInterface $agreementRepository,
        AgreementHistoryCheckerInterface $agreementHistoryChecker,
        array $contexts,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->agreementRepository = $agreementRepository;
        $this->agreementHistoryChecker = $agreementHistoryChecker;
        $this->contexts = $contexts;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $context = $this->getFormClass($builder);
        Assert::notNull($context);

        $agreements = $this->getAgreements($context);

        $builder
            ->add('agreements', AgreementCollectionType::class, [
                'entries' => $agreements,
                'required' => false,
                'label' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $formEvent) use ($context): void {
                if (!$formEvent->getForm()->getErrors(true)) {
                    $event = new AgreementCheckedEvent($context, $formEvent);
                    $this->eventDispatcher->dispatch($event);
                }
            })
        ;
    }

    /**
     * Moved to configuration using \BitBag\SyliusAgreementPlugin\DependencyInjection\DependencyInjectionExtension and %sylius_agreement_plugin.extended_form_types% parameter
     */
    public static function getExtendedTypes(): array
    {
        return [];
    }

    private function getAgreements(?string $formName): ?array
    {
        if (null === $formName) {
            return null;
        }

        $agreements = $this->agreementRepository->findAgreementsByContext($formName);

        /** @var AgreementInterface $agreement */
        foreach ($agreements as $agreement) {
            $agreement->setApproved($this->agreementHistoryChecker->isAgreementAccepted($agreement));
        }

        return $agreements;
    }

    private function getFormClass(FormBuilderInterface $builder): ?string
    {
        $formName = get_class($builder->getType()->getInnerType());

        foreach ($this->contexts as $context => $val) {
            if (in_array($formName, $val, true)) {
                return $context;
            }
        }

        return null;
    }
}
