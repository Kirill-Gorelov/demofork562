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
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="string", length=255)
     */
    private $image = '';

    /**
     * @ORM\Column(name="parent", type="string")
     */
    private $parent = 0;

    /**
     * @ORM\Column(name="category_type_id", type="integer")
     */
    private $category_type_id;

    /**
     *
     * @ORM\Column(name="code", type="string")
     */
    private $code = '';

    /**
     * @var Locale
     *
     * @ORM\Column(type="locale", name="language")
     */
    private $locale;

   /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="date")
     */
    private $date;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="edited_on")
     */
    private $editedOn;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="creator_user_id")
     */
    private $creatorUserId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="editor_user_id")
     */
    private $editorUserId;

     /**
     * @var Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="Backend\Modules\EnerIblocks\Domain\CategorysMeta\CategoryMeta",
     *     cascade="persist",
     *     mappedBy="category_meta"
     * )
     */
    private $cmeta;

    public function __construct(){
        $this->locale = Locale::workingLocale();
        $this->date = new \DateTime();
        $this->cmeta = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
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

    public function setParent($parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setCategoryTypeId($category_type_id)
    {
        $this->category_type_id = $category_type_id;
    }

    public function getCategoryTypeId()
    {
        return $this->category_type_id;
    }

    public function getCreatorUserId()
    {   
        if (is_null($this->creatorUserId) == false) { //ужасное условие
            $user = new User($this->creatorUserId);
            return $user->getEmail();
        }

        return '';
    }

    public function getEditorUserId()
    {
        if (is_null($this->editorUserId) == true) { //ужасное условие
            return '';
        }

        $user = new User($this->editorUserId);
        if($user->getEmail() == $this->getCreatorUserId()){
            return '';
        }

        return $user->getEmail();
    }

    /**
     * @return locale
     */
    public function getLocale(Locale $locale = null): Locale
    {
        if ($locale === null) {
            $locale = Locale::workingLocale();
        }

        return $this->locale = $locale;
    }

    /**
     * @param locale $locale
     */
    public function setLocale(Locale $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getEditedOn(){
        
        if (is_null($this->editedOn)) {
            return '';
        }

        return date_format($this->editedOn, 'Y-m-d H:i');
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

    /**
     * @return Collection
     */
    public function getCmeta()
    {
        return $this->cmeta;
    }
    /**
     * @param Collection $meta
     */
    public function setCmeta(Collection $cmeta): void
    {
        $this->cmeta = $cmeta;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->locale = $this->locale;
        $this->editedOn = new DateTime();
        $this->creatorUserId = $this->editorUserId = Authentication::getUser()->getUserId();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->editedOn = new DateTime();
        $this->editorUserId = Authentication::getUser()->getUserId();
    }
}