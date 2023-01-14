<?php

namespace Plugin\Square42\Form\Extension;

use Eccube\Form\Type\Shopping\OrderType;
use Eccube\Repository\PaymentRepository;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;

class Square42Extension extends AbstractTypeExtension
{
    /** @var PaymentRepository */
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['skip_add_form']) {
            return;
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            $form->add('square_payment_token', HiddenType::class, [
                'required' => false,
                'mapped' => true,
            ]);
        });
    }

    public static function getExtendedTypes(): iterable
    {
        yield OrderType::class;
    }
}
