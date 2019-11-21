<?php
namespace Backend\Modules\EnerSliders\Domain\Sliders;

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
 * @ORM\Table(name="sliders")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerSliders\Domain\Sliders\SliderRepository")
 * @ORM\HasLifecycleCallbacks
 */

class Slider
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
     * @var Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="Backend\Modules\EnerSliders\Domain\Slides\Slide",
     *     cascade="persist",
     *     mappedBy="pagesliders"
     * )
     */
    private $slide;

    public function __construct(){
        $this->locale = Locale::workingLocale();
        $this->date = new \DateTime();
        $this->slide = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getSlide()
    {
        return $this->slide;
    }
    /**
     * @param Collection $slide
     */
    public function setSlide(Collection $slide): void
    {
        $this->slide = $slide;
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
            'EnerSliders',
            'Sliders',
            'Sliders',
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
