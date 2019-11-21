<?php
namespace Backend\Modules\EnerBanners\Domain\Banners;

use Doctrine\ORM\Mapping as ORM;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\User;
use Backend\Core\Language\Locale;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Backend\Core\Engine\Model;
use Common\ModuleExtraType;

/**
 *
 * @ORM\Table(name="banners")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerBanners\Domain\Banners\BannerRepository")
 * @ORM\HasLifecycleCallbacks
 */

class Banner
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
     * @ORM\Column(type="string", name="link", nullable=true)
     */
    private $link;

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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $imagemd = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $imagelg = '';

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
     * @ORM\Column(type="datetime", name="date_views")
     */
    private $date_views;

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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    public $tpl;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $moduleExtraId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="views_count")
     */
    public $views_count = 0;


    public function __construct(){
        $this->locale = Locale::workingLocale();
        $this->date = new \DateTime();
        // $this->date_views = new \DateTime();
    }

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

    /**
     * @return string
     */
    public function getImageLg(): string
    {
        return $this->imagelg;
    }

    /**
     * @param string $imagelg
     */
    public function setImageLg($imagelg): void
    {
        if(!$imagelg){
            $imagelg = '';
        }
        $this->imagelg = $imagelg;
    }

    public function getImageMd(): string
    {
        return $this->imagemd;
    }

    /**
     * @param string $imagemd
     */
    public function setImageMd($imagemd): void
    {
        if(!$imagemd){
            $imagemd = '';
        }
        $this->imagemd = $imagemd;
    }

    public function getImageMb(): string
    {
        return $this->imagemb;
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
    public function getTpl()
    {
        return (string)$this->tpl;
    }

    /**
     * @param string $tpl
     */
    public function setTpl(string $tpl): void
    {
        $this->tpl = $tpl;
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
     * @return string
     */
    public function getDateViews()
    {
        // return date_format($this->date_views, 'Y-m-d H:i');
        if (is_null($this->date_views)) {
            return new DateTime();
        }
        return $this->date_views;
    }

    /**
     * @param DateTime $date
     */
    public function setDateViews(DateTime $date_views): void
    {
        $this->date_views = $date_views;
    }

    public function getModuleExtraId(): int
    {
        return $this->moduleExtraId;
    }

    public function getViewsCount(): int
    {
        if (is_null($this->views_count)) {
            return 0;
        }

        return $this->views_count;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->locale = $this->locale;
        $this->editedOn = new DateTime();
        $this->creatorUserId = $this->editorUserId = Authentication::getUser()->getUserId();

        $this->moduleExtraId = Model::insertExtra(
            ModuleExtraType::widget(),
            'EnerBanners',
            'Banners',
            'Banners',
            [],
            false
        );
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->editedOn = new DateTime();
        $this->editorUserId = Authentication::getUser()->getUserId();
    }

    /**
     * Update module extra data
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
     public function updateModuleExtraData()
     {
         // Update ModuleExtra data
         Model::updateExtra(
             $this->getModuleExtraId(),
             'data',
             [
                 'gallery_id' => $this->id,
                 'extra_label' => $this->getTitle(),
                 'edit_url' => Model::createUrlForAction(
                     'PageslidersEdit',
                     'Pagesliders',
                     null,
                     ['id' => $this->getId()]
                 ),
             ]
         );
 
         // Update hidden
         Model::updateExtra(
             $this->moduleExtraId,
             'hidden',
             false
         );
     }
 
     /**
      * @ORM\PostRemove
      */
     public function onPostRemove()
     {
         Model::deleteExtraById(
             $this->moduleExtraId,
             true
         );
     }

}
