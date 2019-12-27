<?php
namespace Backend\Modules\EnerShop\Domain\Orders;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Backend\Core\Engine\Model;
use Common\ModuleExtraType;

/**
 *
 * @ORM\Table(name="shop_order")
 * @ORM\Entity(repositoryClass="Backend\Modules\EnerShop\Domain\Orders\OrderRepository")
 * @ORM\HasLifecycleCallbacks
 */

class Order
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
     * @ORM\Column(type="string", name="order_number")
     */
    private $orderNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="order_id_acquiring")
     */
    private $orderIdAcquiring;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", name="id_user")
     */
    private $idUser;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id_delivery")
     */
    private $idDelivery;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id_pay")
     */
    private $idPay;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id_status")
     */
    private $idStatus;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="price_delivery")
     */
    private $priceDelivery;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="price")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="contact_adress")
     */
    private $contactAdress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="contact_email")
     */
    private $contactFio;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="contact_email")
     */
    private $contactEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="contact_phone")
     */
    private $contactPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments = '';

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentsManadger = '';

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="date")
     */
    private $date;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", name="date_edit")
     */
    private $dateEdit;


    /*******************/

    public function __construct(){
        $this->date = new \DateTime();
    }

    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getOrderNumber(){
		return $this->orderNumber;
	}

	public function setOrderNumber($orderNumber){
		$this->orderNumber = $orderNumber;
	}

	public function getOrderIdAcquiring(){
		return $this->orderIdAcquiring;
	}

	public function setOrderIdAcquiring($orderIdAcquiring){
		$this->orderIdAcquiring = $orderIdAcquiring;
	}

	public function getIdUser(){
		return $this->idUser;
	}

	public function setIdUser($idUser){
		$this->idUser = $idUser;
	}

	public function getIdDelivery(){
		return $this->idDelivery;
	}

	public function setIdDelivery($idDelivery){
		$this->idDelivery = $idDelivery;
	}

	public function getIdPay(){
		return $this->idPay;
	}

	public function setIdPay($idPay){
		$this->idPay = $idPay;
	}

	public function getIdStatus(){
		return $this->idStatus;
	}

	public function setIdStatus($idStatus){
		$this->idStatus = $idStatus;
	}

	public function getPriceDelivery(){
		return $this->priceDelivery;
	}

	public function setPriceDelivery($priceDelivery){
		$this->priceDelivery = $priceDelivery;
	}

	public function getPrice(){
		return $this->price;
	}

	public function setPrice($price){
		$this->price = $price;
	}

	public function getContactAdress(){
		return $this->contactAdress;
	}

	public function setContactAdress($contactAdress){
		$this->contactAdress = $contactAdress;
	}

	public function getContactFio(){
		return $this->contactFio;
	}

	public function setContactFio($contactFio){
		$this->contactFio = $contactFio;
	}

	public function getContactEmail(){
		return $this->contactEmail;
	}

	public function setContactEmail($contactEmail){
		$this->contactEmail = $contactEmail;
	}

	public function getContactPhone(){
		return $this->contactPhone;
	}

	public function setContactPhone($contactPhone){
		$this->contactPhone = $contactPhone;
	}

	public function getComments(){
		return $this->comments;
	}

	public function setComments($comments){
		$this->comments = $comments;
	}

	public function getCommentsManadger(){
		return $this->commentsManadger;
	}

	public function setCommentsManadger($commentsManadger){
		$this->commentsManadger = $commentsManadger;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
	}

	public function getDateEdit(){

		if (is_null($this->dateEdit)) {
            return '';
        }

        return date_format($this->dateEdit, 'Y-m-d H:i');
	}

	public function setDateEdit($dateEdit){
		$this->dateEdit = $dateEdit;
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->dateEdit = new DateTime();
    }

}
