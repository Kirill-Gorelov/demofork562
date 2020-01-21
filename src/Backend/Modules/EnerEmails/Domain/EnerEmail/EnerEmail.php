<?php
namespace Backend\Modules\EnerEmails\Domain\EnerEmail;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Backend\Core\Engine\Authentication;

/**
 *
 * @ORM\Table(name="email_template")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerEmails\Domain\EnerEmail\EnerEmailRepository")
 * @ORM\HasLifecycleCallbacks
 */

class EnerEmail
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
     * @ORM\Column(type="string", name="efrom")
     */
    private $efrom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="email")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="ecopy")
     */
    private $ecopy;
	
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="template")
     */
    private $template;
	
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="subject")
     */
    private $subject;
	
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="created")
     */
    private $created;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="edited")
     */
    private $edited;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="uid")
     */
    private $uid;


    public function __construct() {
        $this->date = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }
	
    public function getEfrom(): string
    {
        return (string)$this->efrom;
    }
	
    public function setEfrom(string $efrom): void
    {
        $this->efrom = $efrom;
    }
	
    public function getEmail(): string
    {
        return (string)$this->email;
    }
	
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
	
    public function getEcopy(): string
    {
        return (string)$this->ecopy;
    }
	
    public function setEcopy(string $ecopy): void
    {
        $this->ecopy = $ecopy;
    }
	
    public function getTemplate(): string
    {
        return (string)$this->template;
    }
	
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }
	
    public function getSubject(): string
    {
        return (string)$this->subject;
    }
	
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
	
    public function getCreated(): DateTime
    {
        return $this->created;
    }
	
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    public function getEdited(): DateTime
    {
        return $this->edited;
    }
	
    public function setEdited(DateTime $edited): void
    {
        $this->edited = $edited;
    }
	
    public function getUid(): int
    {
        return $this->uid;
    }
	
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created = $this->edited = new DateTime();
        $this->uid = Authentication::getUser()->getUserId();
    }



}
