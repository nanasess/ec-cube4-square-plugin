<?php

namespace Plugin\Square42\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $square_payment_token;

    /**
     * @var string
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $card_number_last4;
    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $card_brand;

    public function getSquarePaymentToken(): ?string
    {
        return $this->square_payment_token;
    }

    public function setSquarePaymentToken(?string $square_payment_token): self
    {
        $this->square_payment_token = $square_payment_token;

        return $this;
    }

    public function getCardNumberLast4(): ?string
    {
        return $this->card_number_last4;
    }

    public function setCardNumberLast4(?string $card_number_last4): self
    {
        $this->card_number_last4 = $card_number_last4;

        return $this;
    }

    public function getCardBrand(): ?string
    {
        return $this->card_brand;
    }

    public function setCardBrand(?string $card_brand): self
    {
        $this->card_brand = $card_brand;

        return $this;
    }
}
