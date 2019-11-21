<?php
namespace Backend\Modules\EnerSliders\Domain\Slides;

use Doctrine\ORM\Mapping as ORM;
use Backend\Core\Engine\Authentication;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;

/**
 *
 * @ORM\Table(name="sliders_slide")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerSliders\Domain\Slides\SlideRepository")
 */

class Slide
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
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" = false})
     */
    private $active = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="title")
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="sort")
     */
    private $sort = 0;


    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description = '';


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $image = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="link", nullable=true)
     */
    private $link;

    /**
     * @var Slider
     *
     * @ORM\ManyToOne(
     *     targetEntity="Backend\Modules\EnerSliders\Domain\Sliders\Slider",
     *     inversedBy="slide"
     * )
     * @ORM\JoinColumn(
     *     name="slider_id",
     *     referencedColumnName="id",
     *     onDelete="cascade"
     * )
     */
    private $pagesliders;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="date")
     */
    private $date;

    public function __construct(){
        $this->date = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    //public function setImage(string $image): void
    public function setImage($image): void
    {
        if(!$image){
            $image = '';
        }
        $this->image = $image;
    }

    public function getPagesliders()
    {
        return $this->pagesliders;
    }

    public function setPagesliders($pagesliders)
    {
        $this->pagesliders = $pagesliders;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return (string)$this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return date_format($this->date, 'Y-m-d H:i');
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

}
