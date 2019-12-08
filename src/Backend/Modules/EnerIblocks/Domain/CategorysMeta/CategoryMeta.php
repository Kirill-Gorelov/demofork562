<?php
namespace Backend\Modules\EnerIblocks\Domain\CategorysMeta;

use Doctrine\ORM\Mapping as ORM;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\User;
use Backend\Core\Language\Locale;
use Common\Doctrine\Entity\Meta;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;


/**
 *
 * @ORM\Table(name="category_meta")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMetaRepository")
 */

class CategoryMeta
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean",)
     */
    private $required;

    /**
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     *
     * @ORM\Column(name="code", type="string")
     */
    private $code = '';

    /**
     *
     * @ORM\Column(name="value", type="string")
     */
    private $value = '';

    /**
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(
     *     targetEntity="Backend\Modules\EnerIblocks\Domain\Categorys\Category",
     *     inversedBy="cmeta"
     * )
     * @ORM\JoinColumn(
     *     name="cmeta_id",
     *     referencedColumnName="id",
     *     onDelete="cascade"
     * )
     */
    private $category_meta;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return (bool) $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCategoryMeta()
    {
        return $this->category_meta;
    }

    public function setCategoryMeta($category_meta)
    {
        $this->category_meta = $category_meta;
    }

}