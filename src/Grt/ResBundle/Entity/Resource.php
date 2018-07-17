<?php

namespace Grt\ResBundle\Entity;

use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 *
 *
 * @ORM\Entity(repositoryClass="Grt\ResBundle\Entity\Repository\ResourceRepository")
 * @ORM\Table(name="resources")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class Resource
{
    /**
     * Id resource
     * @Assert\NotBlank()
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * user's IP-address
     * @ORM\Column(type="string",length=100)
     * @var string
     */
    protected $ip;

    /**
     * Login user
     * @ORM\Column(type="string",length=100)
     * @var string
     */
    protected $login;

    /**
     * Annotation
     * @ORM\Column(type="string",length=256)
     * @var string
     */
    protected $annotation;

    /**
     * Term working
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    protected $term;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;


    /**
     * @ORM\Column(name="flag", type="boolean",options={"default" : 0})
     */
    protected $flag;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="resource_doc", fileNameProperty="docFileName")
     *
     * @var File
     */
    private $docFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $docFileName;


    /**
     * @param File|UploadedFile $docFile
     *
     * @return Resource
     */
    public function setDocFile(File $docFile = null)
    {
        $this->docFile = $docFile;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getDocFile()
    {
        return $this->docFile;
    }

    /**
     * @param string $docFileName
     *
     * @return Resource
     */
    public function setDocFileName(string $docFileName)
    {
        $this->docName = $docFileName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocFileName()
    {
        return $this->docFileName;
    }


    /**
     * User that modified this resource
     * @ORM\ManyToOne(targetEntity="Grt\ResBundle\Entity\Admin")
     * @ORM\JoinColumn(name="admin_id", referencedColumnName="id")
     * @var \Grt\ResBundle\Entity\Admin
     */
    protected $admin;

    /**
     * @return Admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param Admin $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * Base this resource
     * @ORM\ManyToOne(targetEntity="Base", inversedBy="resources", cascade={"persist"})
     * @ORM\JoinColumn(name="base_id", referencedColumnName="id")
     * @var \Grt\ResBundle\Entity\Base
     */
    protected $base;

    /**
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @param string $annotation
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
    }

    /**
     * @return mixed
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param mixed $term
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * @return User
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param Base $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    /**
     * Owner this resource
     * @ORM\ManyToOne(targetEntity="User", inversedBy="resources", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var \Grt\ResBundle\Entity\User
     */
    protected $user;

    public function __construct()
    {
        $this->ip = '';
        $this->login = '';
        $this->annotation ='';
        $this->flag = 0;
        $this->term = new \DateTime('2100-01-01');
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }



    public function setUser(\Grt\ResBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * Set updatedAt
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updated = new \DateTime();
    }


    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    function __set($property,$value) {
        if (property_exists($this, $property)) {
            if ($property == 'term'){
                $this->term = new \DateTime($value);
            } else {
                $this->$property = $value;
            }
        }
    }

}
