<?php
namespace Backend\Modules\EnerIblocks\Domain\Categorys;

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
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerIblocks\Domain\Categorys\CategoryRepository")
 */

class Category
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @ORM\Column(name="parent", type="integer")
     */
    private $parent = 0;

    /**
     *
     * @ORM\Column(name="path", type="string")
     */
    private $path = '';

    public function getId()
    {
        return $this->id;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }
}