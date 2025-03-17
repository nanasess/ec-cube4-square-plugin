<?php

namespace Plugin\Square42\Service\Payment\Method;

use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\Order;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Service\Payment\PaymentMethodInterface;
use Eccube\Service\Payment\PaymentResult;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Util\StringUtil;
use Square\Models\CreatePaymentRequest;
use Square\Models\Currency;
use Square\Models\Money;
use Square\SquareClientBuilder;
use Symfony\Component\Form\FormInterface;

class CreditCard implements PaymentMethodInterface
{
    /** @var Order */
    private $Order;
    /** @var FormInterface */
    private $form;

    /** @var OrderStatusRepository */
    private $orderStatusRepository;
    /** @var PurchaseFlow */
    private $shoppingPurchaseFlow;

    public function __construct(
        OrderStatusRepository $orderStatusRepository,
        PurchaseFlow $shoppingPurchaseFlow
    )
    {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->shoppingPurchaseFlow = $shoppingPurchaseFlow;
    }
    public function verify()
    {
        $result = new PaymentResult();
        $result->setSuccess(true);

        return $result;
    }

    public function apply()
    {
        $OrderStatus = $this->orderStatusRepository->find(OrderStatus::PENDING);
        $this->shoppingPurchaseFlow->prepare($this->Order, new PurchaseContext());

        return null;
    }

    public function checkout()
    {
        $client = SquareClientBuilder::init()
            ->accessToken(env('SQUARE_ACCESS_TOKEN'))
            ->environment(env('SQUARE_ENVIRONMENT'))
            ->build();
        $paymentApi = $client->getPaymentsApi();

        $body_sourceId = $this->Order->getSquarePaymentToken();
        $body_idempotencyKey = StringUtil::random(32);
        $body_amountMoney = new Money();
        $body_amountMoney->setAmount($this->Order->getPaymentTotal());
        $body_amountMoney->setCurrency(Currency::JPY);
        $body = new CreatePaymentRequest(
            $body_sourceId,
            $body_idempotencyKey
        );
        $body->setAmountMoney($body_amountMoney);
        $body->setAutocomplete(true);
        // if ($this->Order->getCustomer() instanceof Customer) {
        //     $body->setCustomerId($this->Order->getCustomer()->getId());
        // }
        $body->setLocationId(env('SQUARE_LOCATION_ID'));
        $body->setReferenceId($this->Order->getOrderNo());

        $apiResponse = $paymentApi->createPayment($body);

        if ($apiResponse->isSuccess()) {
            $OrderStatus = $this->orderStatusRepository->find(OrderStatus::NEW);
            $this->Order->setOrderStatus($OrderStatus);

            $this->shoppingPurchaseFlow->commit($this->Order, new PurchaseContext());

            $result = new PaymentResult();
            $result->setSuccess(true);
        } else {
            $OrderStatus = $this->orderStatusRepository->find(OrderStatus::PROCESSING);
            $this->Order->setOrderStatus($OrderStatus);
            $this->shoppingPurchaseFlow->rollback($this->Order, new PurchaseContext());

            $result = new PaymentResult();
            $result->setSuccess(false);
            $errors = $apiResponse->getErrors();
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getDetail();
            }
            $result->setErrors($messages);
        }

        return $result;
    }

    public function setFormType(FormInterface $form)
    {
        $this->form = $form;
    }

    public function setOrder(Order $Order)
    {
        $this->Order = $Order;
    }
}
