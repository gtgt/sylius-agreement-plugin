<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Admin;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin\AgreementTranslationType;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin\AgreementType;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AgreementTypeTest extends TestCase
{

    /**
     * @var mixed|\PHPUnit\Framework\MockObject\MockObject|FormBuilderInterface
     */
    private $builder;

    public function setUp() :void
    {
        $this->builder = $this->createMock(FormBuilderInterface::class);

    }

    /**
     * @dataProvider buildFormDataProvider
     */
    public function testBuildForm(array $modes, array $preparedModes, array $contexts, array $preparedContexts): void
    {

        $this->builder->expects(self::exactly(7))->method('add')
            ->withConsecutive(
                [
                    'code',
                    TextType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.code',
                        'empty_data' => '',
                    ]
                ],
                [
                    'mode',
                    ChoiceType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.mode',
                        'choices' => $preparedModes,
                    ]
                ],
                [
                    'enabled',
                    CheckboxType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.enabled',
                    ]
                ],
                [
                    'orderOnView',
                    IntegerType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.order_on_view',
                    ]
                ],
                [
                    'contexts',
                    ChoiceType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.contexts_label',
                        'multiple' => true,
                        'choices' => $preparedContexts,
                    ]
                ],
                [
                    'publishedAt',
                    DateType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.published_at',
                        'required' => false,
                        'format' => DateType::HTML5_FORMAT,
                        'widget' => 'single_text',
                    ]
                ],
                [
                    'translations',
                    ResourceTranslationsType::class,
                    [
                        'entry_type' => AgreementTranslationType::class,
                        'entry_options' => [
                            'required' => true,
                        ],
                        'label' => 'bitbag_sylius_agreement_plugin.ui.translations',
                    ]
                ]
            )->willReturnSelf();

        $subject = new AgreementType('test', [], $modes, $contexts);
        $subject->buildForm($this->builder, []);
    }

    public function buildFormDataProvider(): array
    {
        return [
            [
                [
                    'test1',
                    'test2'
                ],
                [
                    'bitbag_sylius_agreement_plugin.ui.agreement.modes.test1' => 'test1',
                    'bitbag_sylius_agreement_plugin.ui.agreement.modes.test2' => 'test2'

            ],
                [
                    'test5',
                    'test55'
                ],
                [
                    'bitbag_sylius_agreement_plugin.ui.agreement.contexts.test5' => 'test5',
                    'bitbag_sylius_agreement_plugin.ui.agreement.contexts.test55' => 'test55'
                ]
            ]
        ];
    }
}
