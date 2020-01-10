<?php
namespace Backend\Modules\EnerShop\Domain\StatusOrders;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Backend\Core\Engine\Model;
use Common\ModuleExtraType;

/**
 *
 * @ORM\Table(name="shop_order_status")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerShop\Domain\StatusOrders\StatusOrderRepository")
 */

class StatusOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="title")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="code")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description = '';




    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return (string)$this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return (string)$this->code;
    }

    /**
     * @param string $Code
     */
    public function setCode(string $Code): void
    {
        $this->code = $Code;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

}
