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
     * @ORM\Column(type="string", nullable=true)
     */
    private $square_payment_token;

    public function getSquarePaymentToken(): ?string
    {
        return $this->square_payment_token;
    }

    public function setSquarePaymentToken(?string $square_payment_token): self
    {
        $this->square_payment_token = $square_payment_token;

        return $this;
    }
}
