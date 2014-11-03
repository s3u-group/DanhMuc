<?php
namespace DanhMuc\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persisttence\ObjectManager;
/**
* @ORM\Entity
* @ORM\Table(name="danh_muc")
*/
class DanhMuc
{
	/**
	* @ORM\Column(name="id",type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue
	*/
	private $id;
	/**
	* @ORM\Column
	*/
	private $ten;

	/**
	 * @ORM\Column(name="mo_ta",type="text")
	 */
	private $moTa;


	
	/**
	 * @ORM\OneToMany(targetEntity="DanhMuc", mappedBy="cha", cascade={"remove"})
	 */
	private $cons;

	/**
	 * @ORM\ManyToOne(targetEntity="DanhMuc")
	 * @ORM\JoinColumn(name="cha", referencedColumnName="id")
	 */
	private $cha;	

	private $cap;

	public function getId()
	{
		return $this->id;
	}


	public function setTen($ten)
	{
		$this->ten=$ten;
	}

	public function getTen()
	{
		return $this->ten;

	}



	public function setMoTa($moTa)
	{
		$this->moTa=$moTa;
	}

	public function getMoTa()
	{
		return $this->moTa;
	}


	public function setCha($cha)
	{
		$this->cha=$cha;
	}

	public function getCha()
	{
		return $this->cha;
	}

	public function getTenCha()
	{
		$cha=$this->cha;
		if($cha)
			return $cha->getTen();
	}

	public function getIdCha()
	{
		$cha=$this->cha;
		if($cha)
			return $cha->getId();
	}

	public function setCap($cap)
	{
		$this->cap=$cap;
	}

	public function getCap()
	{
		return $this->cap;
	}

}
?>