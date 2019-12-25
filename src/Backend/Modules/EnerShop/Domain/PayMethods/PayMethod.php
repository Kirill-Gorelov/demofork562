<?php
namespace Backend\Modules\EnerShop\Domain\PayMethods;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Backend\Core\Engine\Model;
use Common\ModuleExtraType;

/**
 *
 * @ORM\Table(name="shop_method_pay")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerShop\Domain\PayMethods\PayMethodRepository")
 */

class PayMethod
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
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" = false})
     */
    private $active = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="image")
     */
    private $image = '';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="sort")
     */
    private $sort = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="processor")
     */
    private $processor;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="price")
     */
    private $price = 0;


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

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image): void
    {
        if(!$image){
            $image = '';
        }
        $this->image = $image;
    }

    /**
     * @return integer
     */
    public function getSort()
    {
        return (int)$this->sort;
    }

    /**
     * @param integer $sort
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return string
     */
    public function getProcessor(): string
    {
        return (string)$this->processor;
    }

    /**
     * @param string $processor
     */
    public function setProcessor($processor): void
    {
        if(!$processor){
            $processor = '';
        }
        $this->processor = $processor;
    }

    /**
     * @return integer
     */
    public function getPrice()
    {
        return (int)$this->price;
    }

    /**
     * @param integer $Price
     */
    public function setPrice(int $Price): void
    {
        $this->price = $Price;
    }

}
